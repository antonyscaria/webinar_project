<?php
namespace app\components;

use Yii;
use yii\base\Component;
use GuzzleHttp\Client;
use yii\helpers\FileHelper;

class GotoWebinar extends Component
{
    private $client;
    private $baseUrl;
    private $accessToken;
    private $refreshToken;
    private $clientId;
    private $clientSecret;
    private $organizerKey;
    private $tokenFile;

    public function init()
    {
        parent::init();

        $config = Yii::$app->params['gotoWebinar'];

        $this->baseUrl = $config['base_url'];
        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->organizerKey = $config['organizer_key'];
        $this->tokenFile = Yii::getAlias('@runtime/gotowebinar_token.json');

        $this->loadTokens();

        $this->createClient();
    }

    private function createClient()
    {
        $this->client = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    private function loadTokens()
    {
        if (file_exists($this->tokenFile)) {
            $tokens = json_decode(file_get_contents($this->tokenFile), true);
            $this->accessToken = $tokens['access_token'] ?? null;
            $this->refreshToken = $tokens['refresh_token'] ?? null;
        } else {
            throw new \Exception("Token file missing. Run create_token_file.php to generate it.");
        }
    }

    private function saveTokens()
    {
        FileHelper::createDirectory(dirname($this->tokenFile));
        file_put_contents($this->tokenFile, json_encode([
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken
        ], JSON_PRETTY_PRINT));
    }

    private function refreshAccessToken()
    {
        try {
            $response = (new Client())->post('https://authentication.logmeininc.com/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $this->refreshToken,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            if (!empty($data['access_token']) && !empty($data['refresh_token'])) {
                $this->accessToken = $data['access_token'];
                $this->refreshToken = $data['refresh_token'];
                $this->saveTokens();
                $this->createClient();

                Yii::info("GotoWebinar token refreshed successfully.");
                return true;
            }
        } catch (\Exception $e) {
            Yii::error("GotoWebinar token refresh failed: " . $e->getMessage());
        }

        return false;
    }

    private function request($method, $url, $options = [])
    {
        try {
            $response = $this->client->$method($url, $options);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $status = $response ? $response->getStatusCode() : null;
            $body = $response ? json_decode($response->getBody(), true) : [];

            if (($status == 401) || ($status == 403 && isset($body['msg']) && $body['msg'] === "Invalid token passed")) {
                Yii::info("Token expired. Refreshing token...");
                if ($this->refreshAccessToken()) {
                    return $this->request($method, $url, $options); // Retry after refresh
                }
            }

            throw $e;
        }
    }

    public function createEvent($webinar)
    {
        $url = "{$this->baseUrl}/organizers/{$this->organizerKey}/webinars";
        $data = [
            'subject' => $webinar->name,
            'description' => $webinar->description,
            'times' => [[
                'startTime' => date(DATE_ATOM, strtotime('+1 week')),
                'endTime' => date(DATE_ATOM, strtotime('+1 week +1 hour'))
            ]]
        ];
        $result = $this->request('post', $url, ['json' => $data]);
        return $result['webinarKey'] ?? null;
    }

    public function updateEvent($webinar)
    {
        if (!$webinar->event_id) return false;
        $url = "{$this->baseUrl}/organizers/{$this->organizerKey}/webinars/{$webinar->event_id}";
        $data = [
            'subject' => $webinar->name,
            'description' => $webinar->description,
        ];
        $this->request('put', $url, ['json' => $data]);
        return true;
    }

    public function registerUser($eventId, $user)
    {
        $url = "{$this->baseUrl}/organizers/{$this->organizerKey}/webinars/{$eventId}/registrants";
        $data = [
            'firstName' => $user['firstName'],
            'lastName' => $user['lastName'],
            'email' => $user['email']
        ];
        return $this->request('post', $url, ['json' => $data]);
    }
}

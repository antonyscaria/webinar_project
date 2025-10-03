<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "webinar_registrants".
 *
 * @property int $id
 * @property int $webinar_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $registrant_key
 * @property string|null $join_url
 * @property string|null $status
 *
 * @property Webinar $webinar
 */
class WebinarRegistrant extends ActiveRecord
{
    public static function tableName()
    {
        return 'webinar_registrants';
    }

    public function rules()
    {
        return [
            [['webinar_id', 'first_name', 'last_name', 'email'], 'required'],
            [['webinar_id'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [['email'], 'email'],

            // Ensure email is unique per webinar
            [['email'], 'unique', 'targetAttribute' => ['email', 'webinar_id'], 'message' => 'This email is already registered for this webinar.'],

            [['registrant_key', 'join_url', 'status'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'webinar_id'    => 'Webinar ID',
            'first_name'    => 'First Name',
            'last_name'     => 'Last Name',
            'email'         => 'Email Address',
            'registrant_key'=> 'Registrant Key',
            'join_url'      => 'Join URL',
            'status'        => 'Status',
        ];
    }

    /**
     * Relation to Webinar model
     */
    public function getWebinar()
    {
        return $this->hasOne(Webinar::class, ['id' => 'webinar_id']);
    }

    /**
     * Register a user both in GoToWebinar and save locally.
     */
    public static function registerToWebinar($eventId, $webinarId, $userData)
    {
        if (empty($userData) || !is_array($userData)) {
            throw new \InvalidArgumentException("User data is missing or invalid.");
        }

        $gotoWebinar = Yii::$app->gotoWebinar;

        // Register in GoToWebinar API
        $result = $gotoWebinar->registerUser($eventId, [
            'firstName' => $userData['first_name'] ?? '',
            'lastName'  => $userData['last_name'] ?? '',
            'email'     => $userData['email'] ?? '',
        ]);

        if (!$result || !is_array($result)) {
            throw new \Exception("Failed to register user to GoToWebinar.");
        }

        // Save locally
        $registrant = new self();
        $registrant->webinar_id     = $webinarId; // Local DB webinar id
        $registrant->first_name     = $userData['first_name'] ?? '';
        $registrant->last_name      = $userData['last_name'] ?? '';
        $registrant->email          = $userData['email'] ?? '';
        $registrant->registrant_key = $result['registrantKey'] ?? null;
        $registrant->join_url       = $result['joinUrl'] ?? null;
        $registrant->status         = $result['status'] ?? null;

        if ($registrant->save()) {
            return $registrant;
        }

        return false;
    }
}

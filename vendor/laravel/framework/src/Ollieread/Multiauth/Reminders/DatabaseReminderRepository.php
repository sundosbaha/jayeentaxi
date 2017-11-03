<?php namespace Ollieread\Multiauth\Reminders;

use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Auth\Reminders\RemindableInterface;

class DatabaseReminderRepository implements ReminderRepositoryInterface
{

    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * The reminder database table.
     *
     * @var string
     */
    protected $table;

    /**
     * The hashing key.
     *
     * @var string
     */
    protected $hashKey;

    /**
     * The number of seconds a reminder should last.
     *
     * @var int
     */
    protected $expires;

    /**
     * Create a new reminder repository instance.
     *
     * @param  \Illuminate\Database\Connection $connection
     * @param  string $table
     * @param  string $hashKey
     * @param  int $expires
     * @return void
     */
    public function __construct(Connection $connection, $table, $hashKey, $expires = 60)
    {
        $this->table = $table;
        $this->hashKey = $hashKey;
        $this->expires = $expires * 60;
        $this->connection = $connection;
    }

    /**
     * Create a new reminder record and token.
     *
     * @param  \Illuminate\Auth\Reminders\RemindableInterface $user
     * @return string
     */
    public function create(RemindableInterface $user, $type)
    {
        $email = $user->getReminderEmail();

        // We will create a new, random token for the user so that we can e-mail them
        // a safe link to the password reset form. Then we will insert a record in
        // the database so that we can verify the token within the actual reset.
        $token = $this->createNewToken($user);

        $this->getTable()->insert($this->getPayload($email, $token, $type));

        return $token;
    }

    /**
     * Create a new token for the user.
     *
     * @param  \Illuminate\Auth\Reminders\RemindableInterface $user
     * @return string
     */
    public function createNewToken(RemindableInterface $user)
    {
        $email = $user->getReminderEmail();

        $value = str_shuffle(sha1($email . spl_object_hash($this) . microtime(true)));

        return hash_hmac('sha1', $value, $this->hashKey);
    }

    /**
     * Begin a new database query against the table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getTable()
    {
        return $this->connection->table($this->table);
    }

    /**
     * Build the record payload for the table.
     *
     * @param  string $email
     * @param  string $token
     * @return array
     */
    protected function getPayload($email, $token, $type)
    {
        return array('type' => $type, 'email' => $email, 'token' => $token, 'created_at' => new Carbon);
    }

    /**
     * Determine if a reminder record exists and is valid.
     *
     * @param  \Illuminate\Auth\Reminders\RemindableInterface $user
     * @param  string $token
     * @return bool
     */
    public function exists(RemindableInterface $user, $token, $type)
    {
        $email = $user->getReminderEmail();

        $reminder = $this->getTable()->where('email', $email)->where('token', $token)->where('type', $type)->first();

        return $reminder && !$this->reminderExpired($reminder);
    }

    /**
     * Determine if the reminder has expired.
     *
     * @param  object $reminder
     * @return bool
     */
    protected function reminderExpired($reminder)
    {
        $createdPlusHour = strtotime($reminder->created_at) + $this->expires;

        return $createdPlusHour < $this->getCurrentTime();
    }

    /**
     * Get the current UNIX timestamp.
     *
     * @return int
     */
    protected function getCurrentTime()
    {
        return time();
    }

    /**
     * Delete a reminder record by token.
     *
     * @param  string $token
     * @return void
     */
    public function delete($token, $type)
    {
        $this->getTable()->where('token', $token)->where('type', $type)->delete();
    }

    /**
     * Delete expired reminders.
     *
     * @return void
     */
    public function deleteExpired()
    {
        $expired = Carbon::now()->subSeconds($this->expires);

        $this->getTable()->where('created_at', '<', $expired)->delete();
    }

    /**
     * Get the database connection instance.
     *
     * @return \Illuminate\Database\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

}
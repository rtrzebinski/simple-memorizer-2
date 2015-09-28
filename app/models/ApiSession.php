<?php

/**
 * Api session eloquent model
 * 
 * Stores information about active api sessions of user
 * 
 * Database fields
 * @property int $id Id
 * @property int $user_id User id
 * @property string $auth_token Auth token
 * @property string $client_name Client name
 * @property string $client_ip Client ip
 * @property Carbon\Carbon $created_at Created at
 * @property Carbon\Carbon $updated_at Updated at
 * 
 * Relations fields
 * @property \User $user Related User
 */
class ApiSession extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'api_sessions';

	/**
	 * User relation
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

}

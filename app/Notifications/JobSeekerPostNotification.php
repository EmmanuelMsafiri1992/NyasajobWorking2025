<?php
/**
 * JobClass - Job Board Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com/jobclass
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Notifications;

use App\Helpers\Date;
use App\Helpers\UrlGen;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;

class JobSeekerPostNotification extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $post;
	
	public function __construct($post)
	{
		$this->post = $post;
	}
	
	public function via($notifiable)
	{
		return ['mail'];
	}
	
	public function toMail($notifiable)
	{
		$postUrl = UrlGen::post($this->post);
		
		return (new MailMessage)
			->subject(trans('mail.job_seeker_post_notification_title'))
			->greeting(trans('mail.job_seeker_post_notification_content_1', ['userName' => $notifiable->name]))
			->line(trans('mail.job_seeker_post_notification_content_2', [
				'title' => $this->post->title,
				'companyName' => $this->post->company_name,
				'countryName' => $this->post->country->name ?? ''
			]))
			->line(trans('mail.job_seeker_post_notification_content_3', [
				'postUrl' => $postUrl,
				'title'   => $this->post->title,
			]))
			->action(trans('mail.job_seeker_post_notification_action'), $postUrl)
			->line(trans('mail.job_seeker_post_notification_content_4'))
			->salutation(trans('mail.footer_salutation', ['appName' => config('app.name')]));
	}
}
<?php

namespace App\Mail;

use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $company_name = BusinessSetting::where('key', 'business_name')->first()->value;
        $data=EmailTemplate::where('type','store')->where('email_type', 'contact-us')->first();
        $template=$data?$data->email_template:2;
        $url = '';
        $name = $this->name;
        $title = Helpers::text_variable_data_format( value:$data['title']??'',name:$name??'');
        $body = Helpers::text_variable_data_format( value:$data['body']??'',name:$name??'');
        $footer_text = Helpers::text_variable_data_format( value:$data['footer_text']??'',name:$name??'');
        $copyright_text = Helpers::text_variable_data_format( value:$data['copyright_text']??'',name:$name??'');
        return $this->subject(translate('Contact Us'))->view('email-templates.new-email-format-'.$template, ['company_name'=>$company_name,'data'=>$data,'title'=>$title,'body'=>$body,'footer_text'=>$footer_text,'copyright_text'=>$copyright_text,'url'=>$url]);
    }
}

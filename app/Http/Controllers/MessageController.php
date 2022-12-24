<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Message;
use App\Models\User;
use App\Models\Category;
use App\Notifications\sendUserMessageNotification;
use Illuminate\Support\Facades\Notification;
// use App\Traits\EbulkSmsTrait;
use App\Models\EbulkSmsApi;

class MessageController extends Controller
{
    // use EbulkSmsTrait;
    
    //ebulk sms
    /* EBULKSMS API DETAILS */
    private $json_url = "http://api.ebulksms.com:4433/sendsms.json";
    private $xml_url = "http://api.ebulksms.com:4433/sendsms.xml";
    private $http_get_url = "http://api.ebulksms.com:4433/sendsms";
    private $e_username = 'ralphsunny114@gmail.com';
    private $apikey = 'b7199affae645712ff475bf7cbb13f8a7b260de0';
    private $sender = 'KipTrak';

    private $response = array();

    //Send SMS
    public function sendSMS($recipient, $message)
    {
        $flash = 0;
        $message = substr($message, 0, 160); //Limit this message to one page.
        $Ebulksms = new EbulkSmsApi();
        // $ebulkSmsApi = new EbulkSmsApi();

        if ($recipient) {
            if ($Ebulksms->useJSON($this->json_url, $this->e_username, $this->apikey, $flash, $this->sender, $message, $recipient)) {
                return true;
            } else {
                return 'SMS Failed. Try again later.';
            }
        } else {
            return 'No phone number provided.';
        }
    }

    public function sendVCode($phone, $vcode="")
    {
        $message = '<#> Test message';
        $this->sendSMS($phone, $message);
    }



    public function composeSmsMessage()
    {
        return view('pages.messages.sms.composeMessage');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function composeSmsMessagePost(Request $request)
    {
        $request->validate([
            'topic' => 'required|string',
            'recipients' => 'required|string',
            'message' => 'required|string',
            
        ]);

        $data = $request->all();

        if (empty($data['draftinput'])) {
            $message = new Message();
            $message->type = 'sms';
            $message->topic = $data['topic'];
            $message->recipients = $data['recipients'];
            $message->message = $data['message'];
            $message->message_status = 'sent';
            $message->to = 'users';
            $message->created_by = 1;
            $message->status = 'true';
            $message->save();
    
            return back()->with('success', 'Message Sent Successfully');
        } else {
            $message = new Message();
            $message->type = 'sms';
            $message->topic = $data['topic'];
            $message->recipients = $data['recipients'];
            $message->message = $data['message'];
            $message->message_status = 'draft';
            $message->to = 'users';
            $message->created_by = 1;
            $message->status = 'true';
            $message->save();
    
            return back()->with('success', 'Message Saved As Draft Successfully');
        }
            
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sentSmsMessage()
    {
        $messages = Message::where('type', 'sms')->get();
        return view('pages.messages.sms.sentMessage', compact('messages'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function composeEmailMessage()
    {
        $users = User::where('isSuperAdmin', false)->get();
        return view('pages.messages.email.composeMessage', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function composeEmailMessagePost(Request $request)
    {
        $request->validate([
            'topic' => 'required|string',
            'message' => 'required|string',
            'user' => 'required|string',
        ]);

        $data = $request->all();
        $recipients = User::whereIn('id', $data['user_id'])->get();

        //return $data['user_id']; //["2","3"]

        if (empty($data['draftinput'])) {
            $message = new Message();
            $message->type = 'email';
            $message->topic = $data['topic'];
            $message->recipients = serialize($data['user_id']);
            $message->message = $data['message'];
            $message->message_status = 'sent';
            $message->to = 'users';
            $message->created_by = 1;
            $message->status = 'true';
            $message->save();

            Notification::send($recipients, new sendUserMessageNotification($message));
    
            return back()->with('success', 'Message Sent Successfully');
        } else {
            $message = new Message();
            $message->type = 'email';
            $message->topic = $data['topic'];
            $message->recipients = serialize($data['user_id']);
            $message->message = $data['message'];
            $message->message_status = 'draft';
            $message->to = 'users';
            $message->created_by = 1;
            $message->status = 'true';
            $message->save();
    
            return back()->with('success', 'Message Saved As Draft Successfully');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sentEmailMessage()
    {
        $messages = Message::where('type', 'email')->get();
        return view('pages.messages.email.sentMessage', compact('messages'));
    }

    public function mailCustomersByCategory($selectedCategory, $recipients="")
    {
        $category = Category::where('unique_key', $selectedCategory)->first();
        $selectedCustomers = DB::table("customers")->whereIn('id',explode(",",$recipients))->get();
        return view('pages.messages.email.mailCustomersByCategory', compact('category', 'selectedCustomers', 'recipients'));
    }

    public function mailCustomersByCategoryPost(Request $request, $selectedCategory, $recipients="")
    {
        $category = Category::where('unique_key', $selectedCategory)->first();
        $customers = DB::table("customers")->whereIn('id',explode(",",$recipients));
        $recipients_emails = $customers->pluck('email');
        //$recipients_ids = $customers->pluck('id');

        //for db record purpose
        $recipients_ids = explode(',', $recipients);

        $authUser = auth()->user();

        $data = $request->all();

        $message = new Message();
        $message->type = 'email';
        $message->topic = $data['topic'];
        $message->recipients = serialize($recipients_ids);
        $message->message = $data['message'];
        $message->message_status = 'sent';
        $message->to = 'customers';
        $message->created_by = $authUser->id;
        $message->status = 'true';
        $message->save();

        Notification::route('mail', $recipients_emails)->notify(new sendUserMessageNotification($message));

        return back()->with('success', 'Message Sent Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

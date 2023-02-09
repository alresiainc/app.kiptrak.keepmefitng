<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Http;

use App\Models\Message;
use App\Models\User;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;

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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $message = '<#> Test message';
        $this->sendSMS($phone, $message);
    }

    public function composeSmsMessage()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        return view('pages.messages.sms.composeMessage', compact('authUser', 'user_role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function composeSmsMessagePost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'topic' => 'required|string',
            'recipients' => 'required|string',
            'message' => 'required|string|max:200',
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

            $sms_api_token = 'f7NlJwr24AMV2wjnWOTCtHwVWV2sklkb5cPxO0dpvIPg0pNf8kEIpX4nAQzd';

            //array to string
            $to = $data['recipients']; //'2348066216874, 2347048777792'

            try {
                $response = Http::post('https://www.bulksmsnigeria.com/api/v1/sms/create', [
                    'api_token' => $sms_api_token,
                    'from' => 'BulkSMS.ng',
                    'to' => $to,
                    'body' => $data['message'],
                    'dnd' => '2',
                ]);
        
               $x = json_decode($response);
                if (isset($x->error)) {
                    //return $x->error->message;
                    return back()->with('success', 'SMS Saved Successfully, but not Delivered. Contact Service Providers');
                } else {
                    return back()->with('success', 'SMS Saved & Delivered Successfully');
                }
                
            } catch (Exception $exception) {
                return back()->with('success', 'SMS Saved successfully, but not delivered. Something Went Wrong');
            }
    
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $messages = Message::where('type', 'sms')->get();
        return view('pages.messages.sms.sentMessage', compact('authUser', 'user_role', 'messages'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function composeEmailMessage()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $users = User::where('isSuperAdmin', false)->get();
        return view('pages.messages.email.composeMessage', compact('authUser', 'user_role', 'users'));
    }

    public function composeEmailMessagePost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'topic' => 'required|string',
            'message' => 'required|string',
            'user_id' => 'required|string',
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
            $message->to = 'employees';
            $message->created_by = $authUser->id;
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
            $message->to = 'employees';
            $message->created_by = $authUser->id;
            $message->status = 'true';
            $message->save();
    
            return back()->with('success', 'Message Saved As Draft Successfully');
        }
    }

    //from allEmployees tbl
    public function sendEmployeeMail(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'topic' => 'required|string',
            'message' => 'required|string',
            'employee_id' => 'required',
        ]);

        $data = $request->all();

        //return $data['user_id']; //"2,3"
        
        $user_ids = explode(',', $data['employee_id']); //["2","3"]
        $recipients = User::whereIn('id', $user_ids)->pluck('email');

        $message = new Message();
        $message->type = 'email';
        $message->topic = $data['topic'];
        $message->recipients = serialize($user_ids);
        $message->message = $data['message'];
        $message->message_status = 'sent';
        $message->to = 'employees';
        $message->created_by = $authUser->id;
        $message->status = 'true';
        $message->save();
        
        Notification::route('mail', $recipients)->notify(new sendUserMessageNotification($message));

        return back()->with('success', 'Message Sent Successfully');
        
    }

    //from allAgents tbl
    public function sendAgentMail(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'topic' => 'required|string',
            'message' => 'required|string',
            'agent_id' => 'required',
        ]);

        $data = $request->all();

        //return $data['user_id']; //"2,3"
        
        $user_ids = explode(',', $data['agent_id']); //["2","3"]
        $recipients = User::whereIn('id', $user_ids)->pluck('email');

        $message = new Message();
        $message->type = 'email';
        $message->topic = $data['topic'];
        $message->recipients = serialize($user_ids);
        $message->message = $data['message'];
        $message->message_status = 'sent';
        $message->to = 'agents';
        $message->created_by = $authUser->id;
        $message->status = 'true';
        $message->save();
        
        Notification::route('mail', $recipients)->notify(new sendUserMessageNotification($message));

        return back()->with('success', 'Message Sent Successfully');
        
    }

    //from allcustomers tbl
    public function sendCustomerMail(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'topic' => 'required|string',
            'message' => 'required|string',
            'user_id' => 'required',
        ]);

        $data = $request->all();

        //return $data['user_id']; //"2,3"
        
        $user_ids = explode(',', $data['user_id']); //["2","3"]
        $recipients = Customer::whereIn('id', $user_ids)->pluck('email');

        $message = new Message();
        $message->type = 'email';
        $message->topic = $data['topic'];
        $message->recipients = serialize($user_ids);
        $message->message = $data['message'];
        $message->message_status = 'sent';
        $message->to = 'customers';
        $message->created_by = $authUser->id;
        $message->status = 'true';
        $message->save();

        if (!empty($data['mail_customer_order_id'])) {
            $order_ids = explode(',', $data['mail_customer_order_id']);
            $orders = Order::whereIn('id', $order_ids)->get(); //["2","3"]

            foreach ($orders as $key => $order) {
                if (isset($order->email_message_ids)) {
                    //append to former ids
                    $former_mail_message_ids = unserialize($order->email_message_ids); //["8"]
                    $string_format = implode(',', $former_mail_message_ids); //8,4
                    $string_format_append = $string_format.','.$message->id; //8,4,7
                    $array_format = explode(',', $string_format_append); //["8","4"]

                    $order->update(['email_message_ids'=>serialize($array_format)]);
                } else {
                    return '2';
                    $message_id = explode(',', $message->id);
                    $order->update(['email_message_ids'=>serialize($message_id)]);
                }
            }   
        }
        
        try {
            Notification::route('mail', $recipients)->notify(new sendUserMessageNotification($message));
        } catch (Exception $exception) {
            // return back()->withError($exception->getMessage())->withInput();
            return back()->with('info', 'Mail Server Issue. Message Saved in System. You can Re-send later');
        }
        
        return back()->with('success', 'Message Sent Successfully');
        
    }

    //from allAgents tbl
    public function sendAgentWhatsapp(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        

        $data = $request->all();

        //return $data['user_id']; //"2,3"
        
        $user_id = explode(',', $data['whatsapp_agent_id']); //["2","3"]
        
        $message = new Message();
        $message->type = 'whatsapp';
        $message->topic = 'Whatsapp Message';
        $message->recipients = serialize($user_id);
        $message->message = $data['message'];
        $message->message_status = 'sent';
        $message->to = 'agents';
        $message->created_by = $authUser->id;
        $message->status = 'true';
        $message->save();

        $recepient_phone_number = $data['recepient_phone_number'];
        $text_msg = $data['message'];
        
        $url = "https://wa.me/".$recepient_phone_number."?text=".$text_msg;
        return redirect()->away($url);
        
    }

    //from allEmployees tbl
    public function sendEmployeeWhatsapp(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        

        $data = $request->all();

        //return $data['user_id']; //"2,3"
        
        $user_id = explode(',', $data['whatsapp_employee_id']); //["2","3"]
        
        $message = new Message();
        $message->type = 'whatsapp';
        $message->topic = 'Whatsapp Message';
        $message->recipients = serialize($user_id);
        $message->message = $data['message'];
        $message->message_status = 'sent';
        $message->to = 'employees';
        $message->created_by = $authUser->id;
        $message->status = 'true';
        $message->save();

        $recepient_phone_number = $data['recepient_phone_number'];
        $text_msg = $data['message'];
        
        $url = "https://wa.me/".$recepient_phone_number."?text=".$text_msg;
        return redirect()->away($url);
        
    }

    //from allCustomer tbl, allOrders tbl as entries
    public function sendCustomerWhatsapp(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $data = $request->all();

        //return $data['user_id']; //"2,3"
        
        $user_id = explode(',', $data['whatsapp_customer_id']); //["2","3"]
        
        $message = new Message();
        $message->type = 'whatsapp';
        $message->topic = 'Whatsapp Message';
        $message->recipients = serialize($user_id);
        $message->message = $data['message'];
        $message->message_status = 'sent';
        $message->to = 'customers';
        $message->created_by = $authUser->id;
        $message->status = 'true';
        $message->save();

        if (!empty($data['whatsapp_customer_order_id'])) {
            $order_id = $data['whatsapp_customer_order_id'];
            $order = Order::where('id', $order_id)->first();
            if (isset($order->whatsapp_message_ids)) {

                //append to former ids
                $former_whatsapp_message_ids = unserialize($order->whatsapp_message_ids); //["8"]
                $string_format = implode(',', $former_whatsapp_message_ids); //8,4

                $string_format_append = $string_format.','.$message->id;
                $array_format = explode(',', $string_format_append); //["8","4"]
                
                $order->whatsapp_message_ids = \serialize($array_format);
                $order->save();
            } else {

                $message_id = explode(',', $message->id);
                $order->whatsapp_message_ids = \serialize($message_id);
                $order->save();
            }
        }

        $recepient_phone_number = $data['recepient_phone_number'];
        $text_msg = $data['message'];
        
        $url = "https://wa.me/".$recepient_phone_number."?text=".$text_msg;
        return redirect()->away($url);
    }

    public function sentWhatsappMessageUpdate(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'topic' => 'required|string',
            'message' => 'required|string',
        ]);

        $data = $request->all();
        $message = Message::where('id', $data['message_id'])->first();
        $message->topic = $data['topic'];
        $message->message = $data['message'];
        $message->save();

        $text_msg = $data['message'];

        $url = "https://wa.me/".$recepient_phone_number."?text=".$text_msg;
        return redirect()->away($url);
        
    }

    public function sentWhatsappMessage($source="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $messages = Message::where('type', 'whatsapp')->get();

        $order = "";
        if ($source !== "") {
            $order = Order::where('unique_key', $source);
            if ($order->exists()) {
                $order = $order->first();
                $messages = $order->whatsappMessages();
            }
        }

        return view('pages.messages.whatsapp.whatsapp', compact('authUser', 'user_role', 'messages', 'order'));
    }

    public function sentEmailMessage()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $messages = Message::where('type', 'email')->get();
        return view('pages.messages.email.sentMessage', compact('authUser', 'user_role', 'messages'));
    }

    public function sentEmailMessageUpdate(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $request->validate([
            'topic' => 'required|string',
            'message' => 'required|string',
        ]);

        $data = $request->all();
        $message = Message::where('id', $data['message_id'])->first();
        $user_ids = unserialize($message->recipients);

        if ($message->to !== 'customers') {
            $recipients = User::whereIn('id', $user_ids)->pluck('email');
        } else {
            $recipients = Customer::whereIn('id', $user_ids)->pluck('email');
        }
    
        $message->topic = $data['topic'];
        $message->message = $data['message'];
        $message->save();
        
        Notification::route('mail', $recipients)->notify(new sendUserMessageNotification($message));

        return back()->with('success', 'Message Sent Successfully');
        
    }

    public function mailCustomersByCategory($selectedCategory, $recipients="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        $category = Category::where('unique_key', $selectedCategory)->first();
        $selectedCustomers = DB::table("customers")->whereIn('id',explode(",",$recipients))->get();
        return view('pages.messages.email.mailCustomersByCategory', compact('authUser', 'user_role', 'category', 'selectedCustomers', 'recipients'));
    }

    public function mailCustomersByCategoryPost(Request $request, $selectedCategory, $recipients="")
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
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
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;
        
        //
    }
}

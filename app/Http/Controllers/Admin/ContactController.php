<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Brian2694\Toastr\Facades\Toastr;

class ContactController extends Controller
{
    public function list(Request $request)
    {
        $search = $request->search;
        $contacts = Contact::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(25);

        return view('admin-views.contacts.list', compact('contacts'));
    }

    public function view($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin-views.contacts.view', compact('contact'));
    }

    public function update($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->seen = 1;
        $contact->save();

        Toastr::success(translate('messages.marked_as_seen'));
        return back();
    }

    public function sendMail(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'mail_body' => 'required|string',
        ]);

        $contact = Contact::findOrFail($id);

        try {
            Mail::raw($request->mail_body, function ($message) use ($contact, $request) {
                $message->to($contact->email)
                        ->subject($request->subject);
            });

            $contact->reply = json_encode([
                'subject' => $request->subject,
                'body' => $request->mail_body,
            ]);
            $contact->seen = 1;
            $contact->save();

            Toastr::success(translate('messages.mail_sent_successfully'));
        } catch (\Exception $e) {
            Toastr::error(translate('messages.mail_sending_failed') . ': ' . $e->getMessage());
        }

        return back();
    }

    public function delete($id)
    {
        Contact::findOrFail($id)->delete();
        Toastr::success(translate('messages.message_deleted'));
        return back();
    }

    public function exportList(Request $request)
    {
        $search = $request->search;
        $contacts = Contact::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        $type = $request->type ?? 'csv';

        $headers = [
            'Content-Type' => $type === 'csv' ? 'text/csv' : 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename=contacts.' . $type,
        ];

        $callback = function () use ($contacts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['#', 'Name', 'Email', 'Subject', 'Message', 'Status', 'Date']);
            foreach ($contacts as $key => $contact) {
                fputcsv($file, [
                    $key + 1,
                    $contact->name,
                    $contact->email,
                    $contact->subject,
                    $contact->message,
                    $contact->seen ? 'Seen' : 'Unseen',
                    $contact->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

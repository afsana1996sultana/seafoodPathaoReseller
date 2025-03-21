<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Image;

class ResellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::guard('admin')->user()->role != '1') {
            abort(404);
        }
        $resellers = User::where('role', 7)->where('is_approved', 1)->latest()->get();
        return view('backend.reseller.index', compact('resellers'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requests()
    {
        if (Auth::guard('admin')->user()->role != '1') {
            abort(404);
        }
        $resellers = User::where('role', 7)->where('is_approved', 0)->latest()->get();
        return view('backend.reseller.requests', compact('resellers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.reseller.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'wallet_balance' => ['nullable'],
        ]);

        if($request->hasfile('nid')){
            $image = $request->file('nid');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(300,300)->save('upload/reseller/'.$name_gen);
            $nid = 'upload/reseller/'.$name_gen;
        }else{
            $nid = '';
        }
        

        $userEmail = User::where('email', $request->email)->first();
        $userPhone = User::where('phone', $request->phone)->first();
        if ($userEmail) {
            $notification = array(
                'message' => 'User email already Created',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }elseif ($userPhone) {
            $notification = array(
                'message' => 'User Phone already Created',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }else {
            if ($request->reseller_discount_percent == null || $request->reseller_discount_percent == '') {
                $request->reseller_discount_percent = get_setting('reseller_discount_percent')->value;
            }
            $user = User::create([
                'name' => $request->name,
                'fb_web_url' => $request->fb_web_url,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 7,
                'reseller_discount_percent' => $request->reseller_discount_percent,
                'nid' => $nid,
                'wallet_balance' => $request->wallet_balance
            ]);
        }
        event(new Registered($user));
        $notification = array(
            'message' => 'Reseller added successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('reseller.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::guard('admin')->user()->role != '1') {
            abort(404);
        }
        $reseller = User::find($id);
        if ($reseller) {
            return view('backend.reseller.show', compact('reseller'));
        } else {
            Session::flash('error', 'Reseller account not found');
            return redirect()->back();
        }
    }

    public function changeStatus($id)
    {
        $reseller = User::find($id);

        if ($reseller) {
            if ($reseller->status == 0) {
                $reseller->status = 1;
            } else {
                $reseller->status = 0;
            }

            $reseller->save();

            Session::flash('success', 'Reseller Status Changed Successfully');
            return redirect()->back();
        } else {
            Session::flash('error', 'Reseller account not found');
            return redirect()->back();
        }
    }

    public function approve($id)
    {
        $reseller = User::find($id);

        if ($reseller) {
            $reseller->is_approved = 1;
            $reseller->status = 1;

            $reseller->reseller_discount_percent = get_setting('reseller_discount_percent')->value;

            $reseller->save();

            Session::flash('success', 'Reseller Approved Successfully');
            return redirect()->back();
        } else {
            Session::flash('error', 'Reseller account not found');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reseller = User::find($id);

        if ($reseller) {
            return view('backend.reseller.edit', compact('reseller'));
        } else {
            Session::flash('error', 'Reseller account not found');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
        ]);

        if ($request->status == Null) {
            $request->status = 0;
        }

        $reseller = User::find($id);

        $reseller->name = $request->name;
        $reseller->fb_web_url = $request->fb_web_url;
        $reseller->phone = $request->phone;
        $reseller->email = $request->email;
        $reseller->status = $request->status;
        $reseller->reseller_discount_percent = $request->reseller_discount_percent;
        if($request->wallet_balance){
            $reseller->wallet_balance = $request->wallet_balance;
        }

        // Nid Card Update
        if($request->hasfile('nid')){
            try {
                if(file_exists($reseller->nid)){
                    unlink($reseller->nid);
                }
            } catch (Exception $e) {

            }
            $nid = $request->nid;
            $nid_photo = time().$nid->getClientOriginalName();
            $nid->move('upload/reseller/',$nid_photo);
            $reseller->nid = 'upload/reseller/'.$nid_photo;
        }else{
            $nid_photo = '';
        }

        $reseller->save();

        Session::flash('success', 'Reseller Info Updated Successfully');
        return redirect()->route('reseller.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reseller = User::find($id);
        if ($reseller) {
            $reseller->delete();

            $notification = array(
                'message' => 'Reseller Deleted Successfully.',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } else {
            Session::flash('error', 'Reseller account not found');
            return redirect()->back();
        }
    }
}

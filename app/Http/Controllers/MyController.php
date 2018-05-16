<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Auth;
use App\User;
use App\MySentences;

class MyController extends Controller
{
	public function getIndex(){
		return view('index');
	}
	public function getMyvideo(){
		return view('myvide');
	}
	public function getVideo(){
		return view('video');
	}
	public function getMySentences($id){
		$transcript = MySentences::where('id_user',$id)->get();
		return view('mysentences',compact('transcript'));
	}
	public function getAddMySentences($id1,$id2,$id3,$id4,$id5,$id6){
		$mysentences=new MySentences();
		$mysentences->id_user= $id5;
		$mysentences->timestart=$id1;
		$mysentences->time=$id2;
		$mysentences->timeend=$id6;
		$mysentences->content=$id3;
		$mysentences->video=$id4;
		$mysentences->save();
		return redirect()->back();
	}
	public function getLogin(){
		return view('dangnhap');
	}
	public function getSignin(){
		return view('dangki');
	}

	public function postSignin(Request $req){
		$this->validate($req,
			[
				'email'         =>  'required|email|unique:users,email',
				'password'      =>  'required|min:6',
				'fullname'      =>  'required',
				're_password'   =>  'required|same:password',
			],
			[
				'email.required'        =>  'Vui lòng nhập email',
				'email.email'           =>  'Không đúng định dạng email',
				'email.unique'          =>  'Email đã có người sử dụng',
				'password.required'     =>  'Vui lòng nhập mật khẩu',
				're_password.same'      =>  'Mật khẩu không giống nhau',
				're_password.required'  =>  'Vui lòng nhập lại mật khẩu',
				'password.min'          =>  'Mật khẩu ít nhất 6 kí tự',
				'fullname.required'     =>  'Vui lòng nhập họ và tên của bạn',
			]);
		$user = new User();
		$user->name = $req->fullname;
		$user->email = $req->email;
		$user->password = Hash::make($req->password);
		$user->save();
		return redirect()->intended('/')->with(['flag'=>'success','message'=>'Đăng nhập thành công']);
	}

	public function postLogin(Request $request){
		$this->validate($request,
			[
				'email'=>'required|email',
				'password'=>'required|min:8'
			],
			[
				'email.required'=>'Vui lòng nhập tên đăng nhập',
				'email.email'=>'Email không hợp lệ',
				'password.required'=>'Vui lòng nhập mật khẩu',
				'password.min'=>'Mật khẩu ít nhất 6 kí tự',
			]
		);
		$email=$request->email;
		$password=$request->password;
		$credentials = array('email'=>$request->email,'password'=>$request->password);
		if(Auth::attempt($credentials)){
			$user=User::where('email',$email)->first();
			$fullname=$user->full_name;
			return redirect()->intended('/')->with(['flag'=>'success','message'=>'Đăng nhập thành công']);
		}
		else{
			return redirect()->back()->with(['flag'=>'danger','message'=>'Đăng nhập không thành công']);
		}

	}
	public function postLogout(){
		Auth::logout();
		return redirect()->route('home');
	}
	public function postXoa($id){
		$product= MySentences::where('id',$id)->delete();
		return redirect()->back()->with('thanhcong','Xóa thành công');
	}
}

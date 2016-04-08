<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

use App\Group;
use App\Message;

class MessagesController extends Controller{

  public function index(Request $request){

    #设置当前取页数
    $per_page = 15;
    if($request->input('per_page')){
      $per_page = $request->input('per_page');
    }

    #设置当前页
    $current_page = 1;
    if($request->input('page')){
      $current_page = $request->input('page');
    }

    Paginator::currentPageResolver(function() use ($current_page) {
      return $current_page;
    });

    $messages_query = Message::orderBy('id', 'desc');
    if($request->input('namespace')){
      $messages_query->where('namespace', $request->input('namespace'));
    }

    $messages = $messages_query->paginate($per_page);

    $messages_array = $messages->toArray();

    $data = $messages_array['data'];
    $meta = [
      'current_page' => $messages_array['current_page'],
      'total' => $messages_array['total'],
      'per_page' => $messages_array['per_page']
    ];
    return $this->responseJson($data, $meta);
  }

  //创建消息
  public function create(Request $request){
    $content = $request->input('content');
    $title = $request->input('title');
    $target_type = $request->input('target_type');
    $targets = $request->input('targets');
    $sender_id = $request->input('sender_id');
    $expiration_time = $request->input('expiration_time');
    $effective_time = $request->input('effective_time');
    $namespace = $request->input('namespace');

    $options = [
      'content' => $content,
      'target_type' => $target_type,
      'targets' => $targets,
      'sender_id' => $sender_id,
      'title' => $title,
      'expiration_time' => $expiration_time,
      'effective_time' => $effective_time,
      'namespace' => $namespace
    ];

    $message = Message::buildWithOptions($options);
    $message->save();
    return $this->responseJson();
  }

  //用户获取未读消息
  public function getUnReadMessage($user_id, Request $request){
    $unreadMessages = Message::getUnRead($user_id);
    return $this->responseJson($unreadMessages);
  }

  //获取未读消息数量
  public function getUnReadMessageCount($user_id, Request $request){
    $unreadMessageCount = Message::getUnReadCount($user_id);
    return $this->responseJson($unreadMessageCount);
  }

  //阅读消息
  public function read(Request $request){
    $user_id = $request->input('user_id');
    $message_id = $request->input('message_id');
    $message = Message::find($message_id);
    $message->readBy($user_id);
    return $this->responseJson();
  }

  //获取已读消息
  public function getReadMessage($user_id){
    $readMessages = Message::getRead($user_id);
    return $this->responseJson($readMessages);
  }

  //获取消息内容
  public function show($message_id){
    $message = Message::find($message_id);
    return $this->responseJson($message);
  }

  //删除消息
  public function destroy($message_id){
    $message = Message::find($message_id);
    $message->delete();
    return $this->responseJson();
  }
}

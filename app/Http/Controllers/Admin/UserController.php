<?php

namespace App\Http\Controllers\Admin;

use Mail;
use Excel;
use Flash;
use Datatable;
use DateTime;
use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\UserWasCreated;
use Bueno\Validations\ValidationException;
use Bueno\Exceptions\AssignedToRoleException;
use Bueno\Repositories\DbUserRepository as UserRepo;
use Bueno\Validations\CreateUserValidator as UserValidator;
use Bueno\Repositories\DbPermissionRepository as PermissionRepo;

class UserController extends Controller
{

    protected $userRepo;
    protected $permissionRepo;
    protected $userValidator;
    protected $email;

    function __construct(UserRepo $userRepo,PermissionRepo $permissionRepo,UserValidator $userValidator)
    {
        $this->userRepo = $userRepo;

        $this->permissionRepo  = $permissionRepo;

        $this->userValidator = $userValidator;

        $this->middleware('access.user');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
//        $users = $this->userRepo->getAllUsers();

        $today = new DateTime();
        $date['end'] = $today->format('m/d/Y');
        $start  = new DateTime('first day of this month');
        $date['start'] = $start->format('m/d/Y');
      $inputs = request()->all();
      $users = $this->userRepo->getUsersListing($inputs);

      $page = 'dashboard';
      $nav = 'users';
        
        return view('admin.users.index',compact('page','date','users','nav'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = $this->permissionRepo->getAllGroups();
        $page = 'form';
        $nav = 'users';
        return view('admin.users.create',compact('page','groups','nav'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        try
        {
          $this->userValidator->fire($request->all());
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.new_user'))->withErrors($e->getErrors())->withInput();
        }

       $user = $this->userRepo->newUser($request);
      if($request->get('email')!=null)
      {event(new UserWasCreated($user));}
       Flash::success('User successfully created');
       return redirect()->route('admin.update_user',$user->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $page = 'user-profile';
        $nav = 'users';
        return view('admin.users.profile',compact('page','nav'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $page = 'user-profile';
      $user = $this->userRepo->getUserById($id);
      $groups = $this->permissionRepo->getAllGroups();
        $nav = 'users';
      return view('admin.users.profile',compact('page','user','groups','nav'));
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

      try
        {
          $this->userValidator->fire($request->all());
          $this->userRepo->checkIfAssigned($id,$request->all());
        }
        catch(ValidationException $e)
        {
          return redirect(route('admin.update_user',$id))->withErrors($e->getErrors())->withInput();
        }
      catch(AssignedToRoleException $e)
      {
        Flash::danger($e->getMessage());
        return redirect()->route('admin.update_user',$id);
      }


      $user = $this->userRepo->updateUser($id,$request);

      Flash::success('User successfully updated');
      return redirect()->route('admin.update_user',$user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function getByEmail($email)
    {   
        return $this->userRepo->getUserDetailByEmail($email);
    }

    public function getByPhone($phone)
    {
        return $this->userRepo->getUserDetailByPhone($phone);
    }

    /**
     *  Exports the array passed to xls file.
     */
    public function export()
    {
      $inputs = request()->all();

        $start = new DateTime($inputs['start']);
        $end = new DateTime($inputs['end']);

        $csv = [
                'Sign up Date',
                'Name',
                'Email',
                'Mobile',
                'Source',
                'Tier',
                'No of Orders',
                // 'Orders value',
                // 'Avg. Order Value',
                'First Order Date',
                'Last Order Date',
                'Email Notification',
                'SMS Notification'];
        $fp = fopen(storage_path().'/dumps/user-export.csv', 'w');
        fputcsv($fp,$csv);
        User::where('created_at','>=',$start)->where('created_at','<=',$end)->where('group_id',2)->chunk(500, function($users) use($fp)
        {
            foreach ($users as $user)
            {
              $user_orders = $user->orders->filter(function($order){
                return (in_array($order->status,[2,3,4,7]));
                });
              // $total_money_spent = $user->total_money_spent;
              $order_count = $user_orders->count();
              $first_order = $user->first_order;
              $last_order = $user->last_order;
             fputcsv($fp, [
              $user->created_at,
              $user->full_name,
              $user->email,
              $user->phone,
              $first_order ? $first_order->source->name : 'Web',
              $user->membership->name,
              $order_count,
              // $total_money_spent,
              // $order_count!=0 ? round($total_money_spent/$order_count,2) : 0,
              $first_order ? $first_order->created_at : 'NA',
              $last_order  ? $last_order->created_at : 'NA',
              $user->email_notify ? 'Yes' : 'No',
              $user->sms_notify ? 'Yes' : 'No'
              ]);
            }
        });

        fclose($fp);

         header('Content-Disposition: attachment; filename="user-export.csv"');
         header("Cache-control: private");
         header("Content-type: application/force-download");
         header("Content-transfer-encoding: binary\n");

         echo readfile(storage_path().'/dumps/user-export.csv');
         exit;
    }

    public function all()
    {
        $keyword = request()->get('q');

      $users = $this->userRepo->getUsersByKeyword($keyword);

      return $users;
    }


  public function allAdmin()
  {
    $keyword = request()->get('q');

    $users = $this->userRepo->getAdminsByKeyword($keyword);

    return $users;

  }


  public function ajaxUsers()
  {
    $sort_index = request()->get('iSortCol_0');
    $sort_order = request()->get('sSortDir_0');
    $index = 'nothing';

    if($sort_index == 'desc')
      $sort_by='DESC';
    else if ($sort_order == 'asc')
      $sort_by='ASC';
    else
      $sort_by='nothing';
    $keyword = request()->get('sSearch');
    if($keyword==null)
      $keyword='';
    $users = User::get();

    $users = $users->filter(function($user) use ($keyword){
      if($keyword!='')
    if(strpos($user->full_name,$keyword)!== false||strpos($user->id,$keyword)!== false||strpos($user->group->name,$keyword)!== false||strpos($user->membership->name,$keyword)!== false||strpos($user->phone,$keyword)!== false)
      return true;
      else
        return false;
      return true;
    });
    switch($sort_index)
    {
      case 0 : if($sort_order=='desc') $users = $users->sortByDesc(function($user) {return $user->id;}); else $users = $users->sortBy(function($user) {return $user->id;}); break;
      case 2 : if($sort_order=='desc') $users = $users->sortByDesc(function($user) {return $user->full_name;}); else $users = $users->sortBy(function($user) {return $user->full_name;}); break;
      case 3 : if($sort_order=='desc') $users = $users->sortByDesc(function($user) {return $user->group->name;}); else $users = $users->sortBy(function($user) {return $user->group->name;}); break;
      case 4 : if($sort_order=='desc') $users = $users->sortByDesc(function($user) {return $user->total_money_spent;}); else $users = $users->sortBy(function($user) {return $user->total_money_spent;}); break;
      case 5 : if($sort_order=='desc') $users = $users->sortByDesc(function($user) {return $user->total_orders;}); else $users = $users->sortBy(function($user) {return $user->total_orders;}); break;
      case 6 : if($sort_order=='desc') $users = $users->sortByDesc(function($user) {return $user->membership->name;}); else $users = $users->sortBy(function($user) {return $user->membership->name;}); break;
      case 7 : if($sort_order=='desc') $users = $users->sortByDesc(function($user) {return $user->created_at;}); else $users = $users->sortBy(function($user) {return $user->created_at;}); break;
    }
      return Datatable::collection($users->take(50))
          ->searchColumns('id','Full Name','Group','Total Spent','Total Orders','Membership','Signed Up')
          ->orderColumns('id','Full Name','Group','Total Spent','Total Orders','Membership','Signed Up')
          ->addColumn('id',function($model){
            return $model->id;
      })
      ->addColumn('User',function($model){
        $display_pic = "<div class='img-responsive product-img avatar' style='background-image:url(" . route('photo.user',$model->avatar_url). ");'></div>";
        return $display_pic;
      })
      ->addColumn('Full Name',function($model){
        return "<a href='".route('admin.update_user',$model->id)."'>".$model->full_name."</a>";
      })
      ->addColumn('Group',function($model){

        $group =  "<label class='label ";
        switch($model->group->id)
        {
          case 1 : $group.="label-danger'>";
                    break;
          case 2 : $group.="label-success'>";
                    break;
          case 3 : $group.="label-info'>";
                    break;
          case 4 : $group.="label-warning'>";
                   break;
          default : $group.="label-primary'>";
                  break;
        }
        $group.=$model->group->name."</label>";
        return $group;
      })
      ->addColumn('Total Spent',function($model){
        return $model->total_money_spent;
      })
      ->addColumn('Total Orders',function($model){
        return $model->total_orders;
      })
      ->addColumn('Membership',function($model){

        $membership = "<span class='label";
        $membership .= "' style='color:".$model->membership->text_color.";background-color:".$model->membership->bg_color.";'>".$model->membership->name."</span>";
        return $membership;

      })
      ->addColumn('Signed Up',function($model){
        return $model->created_at->format('d F,Y');
      })
      ->make();

  }





}

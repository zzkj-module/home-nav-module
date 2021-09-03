<?php
// @author liming
namespace Modules\HomeNav\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\HomeNav\Http\Controllers\Controller;
use Modules\HomeNav\Http\Requests\Admin\HomeNavEditRequest;
use Modules\HomeNav\Entities\Applet;
use Modules\HomeNav\Entities\HomeNav;

class HomeNavController extends Controller
{
    /**
     * 分页列表
     */
    public function list()
    {
        $isShowArr = HomeNav::getIsShowArr();
        $isShowTest = implode("|", $isShowArr);
        return view('homenavview::admin.homenav.list', compact("isShowTest"));
    }

    /**
     * ajax获取列表数据
     */
    public function ajaxList(Request $request)
    {
        $pagesize = $request->input('limit'); // 每页条数
        $page = $request->input('page',1);//当前页
        $where = [];

        //获取总条数
        $count = HomeNav::where($where)->count();

        //求偏移量
        $offset = ($page-1)*$pagesize;
        $list = HomeNav::where($where)
            ->offset($offset)
            ->limit($pagesize)
            ->orderBy("sort")
            ->orderBy("id", "desc")
            ->get();
        foreach ($list as &$item){
            $item['show_pic'] = $item->setPicUrl($item->pic);
        }
        return $this->success(compact('list', 'count'));
    }

    /**
     * 新增|编辑导航图标
     * @param $id
     */
    public function edit(HomeNavEditRequest $request)
    {
        if($request->isMethod('post')) {
            $request->check();
            $data = $request->post();

            if(isset($data["id"])){
                $info = HomeNav::where("id",$data["id"])->first();
                if(!$info) return $this->failed('数据不存在');
            }else{
                $info = new HomeNav();
            }

            $info->name = $data["name"];
            $info->route = $data["route"] ?? "";
            $info->open_type = $data["open_type"] ?? "";
            $info->pic = $data["pic"];
            if(!file_exists($info->pic)) return $this->failed('上传的图标不存在');
            $info->sort = $data["sort"];
            $info->is_show = $data["is_show"];

            if(!$info->save()) return $this->failed('操作失败');
            return $this->success();
        } else {
            $id = $request->input('id') ?? 0;
            if($id > 0){
                $info = HomeNav::where('id',$id)->first();
                $info['show_pic'] = $info->setPicUrl($info->pic);
                $title = "编辑图标";
            }else{
                $info = new HomeNav();
                $title = "新增图标";
            }
            $domain = HomeNav::getDomain();
            if(Schema::hasTable("applet")){
                $applet = Applet::orderBy("id")->get()->toArray();
            }else{
                $applet = [];
            }
            foreach ($applet as &$item){
                $item["params"] = json_decode($item["params"], true);
            }
            return view('homenavview::admin.homenav.edit', compact('info', 'title', 'domain', 'applet'));
        }
    }

    /**
     * 删除导航图标
     */
    public function del(Request $request)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            $info = HomeNav::where('id', $id)->first();
            if (!$info) return $this->failed("数据不存在");
            if(!$info->delete()) return $this->failed("操作失败");
            return $this->success();
        }
        return $this->failed('请求出错.');
    }

    /**
     * 显示|隐藏导航图标
     */
    public function show(Request $request)
    {
        if($request->isMethod('post')){
            $id = $request->input('id');
            $info = HomeNav::where('id', $id)->first();
            if (!$info) return $this->failed("数据不存在");

            $is_show = $request->input("is_show");
            if($is_show === "true"){
                $is_show = 1;
            }else if($is_show === "false"){
                $is_show = 0;
            }
            $isShowArr = HomeNav::getIsShowArr();
            if(!isset($isShowArr[$is_show])) return $this->failed('显示值不存在');

            $info->is_show = $is_show;
            try {
                if (!$info->save()) throw new \Exception("操作失败");
                return $this->success();
            } catch (\Exception $e) {
                return $this->failed($e->getMessage());
            }
        }
        return $this->failed('请求出错.');
    }
}

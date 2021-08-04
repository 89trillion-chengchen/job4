<?php

namespace ctrl;


use framework\util\Singleton;
use service\AnswerService;
use service\GiftCodeService;
use utils\HttpUtil;
use view\JsonView;

class GiftCodeCtrl extends CtrlBase{


    /**
     * GiftCodeCtrl constructor.
     */
    public function __construct()
    {
    }

    public function creatgiftCode(){

        //获取get或post请求数据
        $admin=HttpUtil::getPostData('admin');
        $description=HttpUtil::getPostData('description');
        $count=HttpUtil::getPostData('count');
        $begintime=HttpUtil::getPostData('begintime');
        $endtime=HttpUtil::getPostData('endtime');
        $content=HttpUtil::getPostData('content');
        $type=HttpUtil::getPostData('type');
        $role=HttpUtil::getPostData('role');

        /** @var GiftCodeService $giftCodeService */
        $giftCodeService=Singleton::get(GiftCodeService::class);

        //校验数据
        list($checkResult, $checkNotice) = $giftCodeService->checkUploadParams($admin, $description, $count, $begintime, $endtime, $content, $type);

        if (true!==$checkResult){
            $rspArr = AnswerService::makeResponseArray($checkNotice);
            return new JsonView($rspArr);
        }

        //执行函数
        $result=$giftCodeService->creatGiftCode($admin, $description, $count, $begintime, $endtime, $content, $type,$role);

        return new JsonView($result);

    }


    public function useCode(){
        //获取get或post请求数据
        $admin=HttpUtil::getPostData('admin');
        $role=HttpUtil::getPostData('role');
        $code = HttpUtil::getPostData('code');

        /** @var GiftCodeService $giftCodeService */
        $giftCodeService=Singleton::get(GiftCodeService::class);

        //校验数据
        list($checkResult, $checkNotice) = $giftCodeService->checkParams($admin, $code,$role);
        if (true!==$checkResult){
            $rspArr = AnswerService::makeResponseArray($checkNotice);
            return new JsonView($rspArr);
        }

        //执行函数
        $result=$giftCodeService->useCode($admin,$role,$code);

        return new JsonView($result);

    }

    public function getCodeInfo(){
        $code = HttpUtil::getPostData('code');

        /** @var GiftCodeService $giftCodeService */
        $giftCodeService=Singleton::get(GiftCodeService::class);

        //校验数据
        list($checkResult, $checkNotice) = $giftCodeService->checkParam( $code);
        if (true!==$checkResult){
            $rspArr = AnswerService::makeResponseArray($checkNotice);
            return new JsonView($rspArr);
        }

        //执行函数
        $result=$giftCodeService->getCodeInfo($code);

        return new JsonView($result);

    }

}


?>

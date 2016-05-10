<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 5/9/16
 * Time: 19:42
 */
namespace Topxia\Service\Org;

interface OrgService
{
    public function createOrg($org);

    public function updateOrg($id, $fields);

    public function getOrg($id);

    public function deleteOrg($id);
    /**
     *  获取后台管理组织机构数据
     *  如果没有传orgcode, 默认获取所有
     */
    public function findOrgsByOrgCode($orgCode = null);

    public function isCodeAvaliable($value, $exclude);
}

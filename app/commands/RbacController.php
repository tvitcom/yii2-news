<?php
/**
 * @link http://github.com/tvitcom
 * @copyright Copyright (c) 2017 by tvitcom
 * @license Сreative Сommon Attribution-NonCommercial-ShareAlike 4.0 International
 */

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        //Добавим разрешение readPreviewPost
        $readPreviewPost = $auth->createPermission('readPreviewPost');
        $readPreviewPost->description = 'Preview a post';
        $auth->add($readPreviewPost);
        
        // добавляем роль "guest" и даём роли разрешения:
        $guest = $auth->createRole('guest');
        $auth->add($guest);
        $auth->addChild($guest, $readPreviewPost);
        
        // добавляем разрешение "readPost"
        $readPost = $auth->createPermission('readPost');
        $readPost->description = 'Read post';
        $auth->add($readPost);
        
        // добавляем роль "reader" и даём роли разрешения:
        $reader = $auth->createRole('reader');
        $auth->add($reader);
        $auth->addChild($reader, $guest);
        $auth->addChild($reader, $readPost);
        
        // добавляем разрешение "createPost"
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // добавляем разрешение "updatePost"
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update a post';
        $auth->add($updatePost);
        
        // добавляем разрешение "deletePost"
        $deletePost = $auth->createPermission('deletePost');
        $deletePost->description = 'Delete a post';
        $auth->add($deletePost);

        // добавляем роль "moder" и даём роли разрешения:
        $moder = $auth->createRole('moder');
        $auth->add($moder);
        $auth->addChild($moder, $reader);
        $auth->addChild($moder, $createPost);
        $auth->addChild($moder, $updatePost);
        $auth->addChild($moder, $deletePost);

        /*
         * Add the rule example
        $rule = new \app\rbac\AuthorRule;
        $auth->add($rule);

        // добавляем разрешение "updateOwnPost" и привязываем к нему правило.
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);

        // "updateOwnPost" будет использоваться из "updatePost"
        $auth->addChild($updateOwnPost, $updatePost);

        // разрешаем "автору" обновлять его посты
        $auth->addChild($author, $updateOwnPost);
         */
        
        // добавляем разрешение "createPerson"
        $createPerson = $auth->createPermission('createPerson');
        $createPerson->description = 'Create a person';
        $auth->add($createPerson);
        
        // добавляем разрешение "readPost"
        $readPerson = $auth->createPermission('readPerson');
        $readPerson->description = 'Read info about person';
        $auth->add($readPerson);
        
        // добавляем разрешение "updatePerson"
        $updatePerson = $auth->createPermission('updatePerson');
        $updatePerson->description = 'Update info about person';
        $auth->add($updatePerson);
        
        // добавляем разрешение "createPost"
        $deletePerson = $auth->createPermission('deletePerson');
        $deletePerson->description = 'Delete info about person';
        $auth->add($deletePerson);
        
        // добавляем роль "admin" и даём роли разрешение "updatePost"
        // а также все разрешения роли "author"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $moder);
        $auth->addChild($admin, $createPerson);
        $auth->addChild($admin, $readPerson);
        $auth->addChild($admin, $updatePerson);
        $auth->addChild($admin, $deletePerson);

        // Назначение ролей пользователям. 1 и 2 (это IDs возвращаемые 
        // IdentityInterface::getId()) и обычно реализуемый в модели User.
        $auth->assign($moder, 2);
        $auth->assign($admin, 1);
    }
}
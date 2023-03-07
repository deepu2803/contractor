<?php

declare(strict_types=1);
namespace App\Controller;


class ContractorsController extends AppController

{

    public function beforeFilter(\Cake\Event\EventInterface $event){
        parent::beforeFilter($event);
        $this->loadModel('Users');
        $this->loadModel('UserProfile');
        $this->loadModel('Projects');
        $this->loadModel('Services');
        $this->loadModel('AssignedUsers');
        $this->viewBuilder()->setLayout('scgc_layout');
    }

/*********************MyProfile ********************** */
/*
    public function gcProfile()
    {
        $services = $this->paginate($this->Services);
        $auth = $this->Authentication->getIdentity();
        $id = $auth->id;
        if($auth->user_type == 2){
        $this->viewBuilder()->setLayout('scgc_layout');
        $gc = $this->Users->get($id,['contain' => ['UserProfile']]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $gc->user_services['user_id']=$id;
            $user = $this->Users->patchEntity($gc, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['controller'=>'contractors','action' => 'assigned-project-list']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('gc','services'));
    }else{
        $this->Authentication->logout();
        return $this->redirect(['controller' => 'users', 'action' => 'login']);
    }
    }
*/
/************************************************************* */
    public function gcscProfile()
    {
        $services = $this->paginate($this->Services->find('all')->where(['service_status'=>1]));
        $auth = $this->Authentication->getIdentity();
        $id = $auth->id;
        $this->viewBuilder()->setLayout('scgc_layout');
        $gcsc = $this->Users->get($id,['contain' => ['UserProfile']]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $gcsc->user_services['user_id']=$id;
            $user = $this->Users->patchEntity($gcsc, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['controller'=>'contractors','action' => 'assigned-project-list']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('gcsc','services' , 'auth'));
   
    
    }

/********************************************* */

    public function assignedProjectList(){
        $auth = $this->Authentication->getIdentity();
        $assignedUsers =  $this->paginate($this->Projects->find('all')->contain(['Users','UserProfile'])->where(['AND'=>['accept_status'=>1,'assigned_status'=>1,'contractor'=>$auth->user_type]]));
        $this->set(compact('assignedUsers'));
    }

/*********************Project Details************************ */
    public function projectDetails($id = null)
    {
        $assigned = $this->Projects->get($id, [
            'contain' => ['Users','UserProfile'],
        ]);
        $this->set(compact('assigned'));
    }

/********************************************* */

 /*   public function scProfile(){
            $auth = $this->Authentication->getIdentity();
            $id = $auth->id;
            if($auth->user_type == 3){
            $this->viewBuilder()->setLayout('scgc_layout');
            $sc = $this->Users->get($id,['contain' => ['UserProfile']]);
            $services = $this->paginate($this->Services);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $user = $this->Users->patchEntity($sc, $this->request->getData());
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The user has been saved.'));
                    return $this->redirect(['controller'=>'Contractors','action' => 'assigned-project-list']);
                }
        $this->Flash->error(__('The user could not be saved. Please, try again.'));
    }
    $this->set(compact('sc','services' ));
    }else{
        $this->Authentication->logout();
        return $this->redirect(['controller' => 'users', 'action' => 'login']);
    }
}
*/
}

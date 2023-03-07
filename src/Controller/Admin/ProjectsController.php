<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Form\ContactForm;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\View\View;

class ProjectsController extends AppController{
    public function initialize(): void
    {
        parent::initialize();
         $this->loadModel('UserProfile');
         $this->loadModel('Users');
         $this->loadModel('Projects');
         $this->loadModel('OwnerServices');
         $this->loadModel('UserServices');
         $this->loadModel('Services');
         $this->loadModel('AssignedUsers');
       
    }

    public function unAssignProject(){
        $this->viewBuilder()->setLayout('admin_layout');
        $projects =  $this->paginate($this->Projects->find('all')->contain(['Users','UserProfile'])->where(['OR'=>['assigned_status'=>0,'accept_status'=>0]]));
   
        $this->set(compact(['projects']));
    }

   
    public function projectView($id = null){
        $this->viewBuilder()->setLayout('admin_layout');
        $project = $this->Projects->get($id, [
            'contain' => ['Users','UserProfile'],
        ]);
        $owner_services = $this->OwnerServices->find('all')->contain(['Services'])->where(['project_id'=>$id])->all();
        $contractor = $project->contractor;
        $users = $this->Users->find('all')->contain(['UserProfile'])->where(['user_type'=>$contractor])->all();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $project = $this->Projects->patchEntity($project, $this->request->getData());
            if ($this->Projects->save($project)) {
                $this->Flash->success(__('The project has been saved.'));

                return $this->redirect(['controller'=>'projects','action' => 'unAssignProject','prefix'=>'Admin']);
            }
            $this->Flash->error(__('The project could not be saved. Please, try again.'));
        }
       
        $this->set(compact('project','owner_services','users'));
        

    }
    public function serviceView(){
        if($this->request->is('ajax')){
            $this->autoRender = false;
            $id = $this->request->getQuery('id');
            $user_services = $this->UserServices->find('all')->contain(['Services'])->where(['user_id'=>$id])->all();
            $data = "";
           foreach($user_services as $user){
            $data ='<tr>
               <td>'.$user->service->service.'</td>
               </tr>';
             echo $data; 
            }
        }

    }

    public function accectOwnerProject($id = null){
        $this->autoRender = false;
        $project = $this->Projects->get($id, [
            'contain' => ['Users'],
        ]);

            $project->accept_status = 1;
            if($this->Projects->save($project)){
                $mailer = new Mailer('default');
                $mailer->setTransport('gmail'); //your email configuration name
                $mailer->setFrom(['chandreshck9559@gmail.com' => 'chandresh']);
                $mailer->setTo($project->user->email);
                $mailer->setEmailFormat('text');
                $mailer->setSubject('Approp Your Account');
                $mailer->deliver('Dear Owener Your Project is Accept');
                 $this->Flash->success(__('Accesspetd'));
                return $this->redirect(['controller' => 'Projects',  'prefix' => 'Admin','action' => 'projectView',$id]); 
            }
        
    }

    public function projectDeleteRecover($id = null){
        $this->autoRender = false;
                $project = $this->Projects->get($id, [
                    'contain' => ['Users'],
                ]);

            if($project->accept_status == 1 && $project->assigned_status == 1){
                $project->accept_status = 2;
                $project->assigned_status = 2;
            }else if($project->accept_status == 2 && $project->assigned_status == 2){
                $project->accept_status = 1;
                $project->assigned_status = 1;
            }
            if($project->accept_status == 2 && $project->assigned_status == 2){
            if($this->Projects->save($project)){
                $mailer = new Mailer('default');
                $mailer->setTransport('gmail'); //your email configuration name
                $mailer->setFrom(['chandreshck9559@gmail.com' => 'chandresh']);
                $mailer->setTo($project->user->email);
                $mailer->setEmailFormat('text');
                $mailer->setSubject('Approp Your Account');
                $mailer->deliver('Dear Owener Your Project is Not Accept');
                 $this->Flash->success(__('Your Project is Delete'));
                return $this->redirect(['controller' => 'Projects',  'prefix' => 'Admin','action' => 'unAssignProject']); 
            }
        }else{
            if($this->Projects->save($project)){
            $mailer = new Mailer('default');
            $mailer->setTransport('gmail'); //your email configuration name
            $mailer->setFrom(['chandreshck9559@gmail.com' => 'chandresh']);
            $mailer->setTo($project->user->email);
            $mailer->setEmailFormat('text');
            $mailer->setSubject('Approp Your Account');
            $mailer->deliver('Dear Owener Your Project is start');
             $this->Flash->success(__('Your Project is recover'));
            return $this->redirect(['controller' => 'Projects',  'prefix' => 'Admin','action' => 'assignProject']);
            }
        }
        
    }
    

    public function assign(){
        $this->autoRender = false;
        $assigned = $this->AssignedUsers->newEmptyEntity();
        $data = $this->request->getData();
      
        $project_id = $this->request->getData('project_id');
     
        if ($this->request->is('ajax')) {
            foreach($data['assigned_userid'] as $val){
                $assigned = $this->AssignedUsers->newEmptyEntity();
                $assigned->user_id = $this->request->getData('user_id');
                $assigned->project_id = $this->request->getData('project_id');
                $assigned->assigned_userid = $val;
                $this->AssignedUsers->save($assigned);

            }
            
            if ($this->AssignedUsers->save($assigned)) {
                
               
                
                $project = $this->Projects->get($project_id);
                
                if($project->assigned_status == 0){
                    $project->assigned_status = 1;
                }
                if($this->Projects->save($project)){
                    $tests = array();
                    $name = array();
                    $phone = array();
                    foreach($data['email'] as $email){
                        $tests[] = $email;
                    }
                    $user_name =$this->request->getData('name');
                    foreach($user_name as $name_test){
                        $name[] = $name_test;
                    }
                    $user_phone =$this->request->getData('phone');
                    foreach($user_phone as $phone_test){
                        $phone[] = $phone_test;
                    }

                        $owner_email = $this->request->getData('owner_email');
                        if(isset($owner_email)){
                            $array_email = implode(',',$tests);
                            $array_name = implode(',',$name);
                            $array_phone = implode(',',$phone);
                            $mailer = new Mailer('default');
                            $mailer->setTransport('gmail'); //your email configuration name
                            $mailer->setFrom(['chandreshck9559@gmail.com' => 'chandresh']);
                            $mailer->setTo($owner_email);
                            $mailer->setEmailFormat('html');
                            $mailer->setSubject('Assign Project');
                            $mailer->deliver('Your Project is assigned your contractor detail<br><p>Name : '.$array_name.'</p><br><p>Email : '.$array_email.'</p><br><p>Contact : '.$array_phone.'</p>');
                        }
                        $mailer = new Mailer('default');
                        $mailer->setTransport('gmail'); //your email configuration name
                        $mailer->setFrom(['chandreshck9559@gmail.com' => 'chandresh']);
                        $mailer->setTo($tests);
                        $mailer->setEmailFormat('html');
                        $mailer->setSubject('Assign Project');
                        $mailer->deliver('You have received a new lead');
                      
                   echo 1;
                }
            }
        }
    }
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx assign project xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx//
 
    public function assignProject(){
        $this->viewBuilder()->setLayout('admin_layout');
        $projects =  $this->paginate($this->Projects->find('all')->contain(['Users','UserProfile'])->where(['AND'=>['assigned_status'=>1,'accept_status'=>1]]));
        $this->set(compact(['projects']));
    } 
}

?>
<?php
namespace Fhm\WorkflowBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Fhm\FhmBundle\Entity\Fhm;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class WorkflowAction extends Fhm
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected $validate_check;

    /**
     * @ORM\ManyToMany(targetEntity="Fhm\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="wkfl_action_validate_users")
     */
    protected $validate_users;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $dismiss_check;

    /**
     * @ORM\ManyToMany(targetEntity="Fhm\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="wkfl_action_dismiss_users")
     */
    protected $dismiss_users;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $cancel_check;

    /**
     * @ORM\ManyToMany(targetEntity="Fhm\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="wkfl_action_cancel_users")
     */
    protected $cancel_users;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $upload_check;

    /**
     * @ORM\ManyToMany(targetEntity="Fhm\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="wkfl_action_upload_users")
     */
    protected $upload_users;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $download_check;

    /**
     * @ORM\ManyToMany(targetEntity="Fhm\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="wkfl_action_download_users")
     */
    protected $download_users;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $comment_check;

    /**
     * @ORM\ManyToMany(targetEntity="Fhm\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="wkfl_action_comment_users")
     */
    protected $comment_users;

    /**
     * @ORM\ManyToMany(targetEntity="WorkflowTask", cascade={"persist"})
     * @ORM\JoinTable(name="wkfl_action_task_users")
     */
    protected $tasks;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sort_task;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->validate_check = false;
        $this->validate_users = new ArrayCollection();
        $this->dismiss_check  = false;
        $this->dismiss_users  = new ArrayCollection();
        $this->cancel_check   = false;
        $this->cancel_users   = new ArrayCollection();
        $this->upload_check   = false;
        $this->upload_users   = new ArrayCollection();
        $this->download_check = false;
        $this->download_users = new ArrayCollection();
        $this->comment_check  = false;
        $this->comment_users  = new ArrayCollection();
        $this->tasks          = new ArrayCollection();
        $this->sort_task      = 0;
    }

    /**
     * Set validate_check
     *
     * @param boolean $validate_check
     *
     * @return self
     */
    public function setValidateCheck($validate_check)
    {
        $this->validate_check = $validate_check;

        return $this;
    }

    /**
     * Get validate_check
     *
     * @return boolean $validate_check
     */
    public function getValidateCheck()
    {
        return $this->validate_check;
    }

    /**
     * Get validate_users
     *
     * @return mixed
     */
    public function getValidateUsers()
    {
        return $this->validate_users;
    }

    /**
     * Set validate_users
     *
     * @param ArrayCollection $users
     *
     * @return $this
     */
    public function setValidateUsers(ArrayCollection $users)
    {
        $this->resetValidateUsers();
        $this->validate_users = $users;

        return $this;
    }

    /**
     * Add validate_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function addValidateUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if(!$this->validate_users->contains($user))
        {
            $this->validate_users->add($user);
        }

        return $this;
    }

    /**
     * Remove validate_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function removeValidateUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if($this->validate_users->contains($user))
        {
            $this->validate_users->removeElement($user);
        }

        return $this;
    }

    /**
     * Delete validate_users
     *
     * @return $this
     */
    public function resetValidateUsers()
    {
        $this->validate_users = new ArrayCollection();

        return $this;
    }

    /**
     * Set dismiss_check
     *
     * @param boolean $dismiss_check
     *
     * @return self
     */
    public function setDismissCheck($dismiss_check)
    {
        $this->dismiss_check = $dismiss_check;

        return $this;
    }

    /**
     * Get dismiss_check
     *
     * @return boolean $dismiss_check
     */
    public function getDismissCheck()
    {
        return $this->dismiss_check;
    }

    /**
     * Get dismiss_users
     *
     * @return mixed
     */
    public function getDismissUsers()
    {
        return $this->dismiss_users;
    }

    /**
     * Set dismiss_users
     *
     * @param ArrayCollection $users
     *
     * @return $this
     */
    public function setDismissUsers(ArrayCollection $users)
    {
        $this->resetDismissUsers();
        $this->dismiss_users = $users;

        return $this;
    }

    /**
     * Add dismiss_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function addDismissUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if(!$this->dismiss_users->contains($user))
        {
            $this->dismiss_users->add($user);
        }

        return $this;
    }

    /**
     * Remove dismiss_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function removeDismissUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if($this->dismiss_users->contains($user))
        {
            $this->dismiss_users->removeElement($user);
        }

        return $this;
    }

    /**
     * Delete dismiss_users
     *
     * @return $this
     */
    public function resetDismissUsers()
    {
        $this->dismiss_users = new ArrayCollection();

        return $this;
    }

    /**
     * Set cancel_check
     *
     * @param boolean $cancel_check
     *
     * @return self
     */
    public function setCancelCheck($cancel_check)
    {
        $this->cancel_check = $cancel_check;

        return $this;
    }

    /**
     * Get cancel_check
     *
     * @return boolean $cancel_check
     */
    public function getCancelCheck()
    {
        return $this->cancel_check;
    }

    /**
     * Get cancel_users
     *
     * @return mixed
     */
    public function getCancelUsers()
    {
        return $this->cancel_users;
    }

    /**
     * Set cancel_users
     *
     * @param ArrayCollection $users
     *
     * @return $this
     */
    public function setCancelUsers(ArrayCollection $users)
    {
        $this->resetCancelUsers();
        $this->cancel_users = $users;

        return $this;
    }

    /**
     * Add cancel_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function addCancelUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if(!$this->cancel_users->contains($user))
        {
            $this->cancel_users->add($user);
        }

        return $this;
    }

    /**
     * Remove cancel_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function removeCancelUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if($this->cancel_users->contains($user))
        {
            $this->cancel_users->removeElement($user);
        }

        return $this;
    }

    /**
     * Delete cancel_users
     *
     * @return $this
     */
    public function resetCancelUsers()
    {
        $this->cancel_users = new ArrayCollection();

        return $this;
    }

    /**
     * Set upload_check
     *
     * @param boolean $upload_check
     *
     * @return self
     */
    public function setUploadCheck($upload_check)
    {
        $this->upload_check = $upload_check;

        return $this;
    }

    /**
     * Get upload_check
     *
     * @return boolean $upload_check
     */
    public function getUploadCheck()
    {
        return $this->upload_check;
    }

    /**
     * Get upload_users
     *
     * @return mixed
     */
    public function getUploadUsers()
    {
        return $this->upload_users;
    }

    /**
     * Set upload_users
     *
     * @param ArrayCollection $users
     *
     * @return $this
     */
    public function setUploadUsers(ArrayCollection $users)
    {
        $this->resetUploadUsers();
        $this->upload_users = $users;

        return $this;
    }

    /**
     * Add upload_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function addUploadUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if(!$this->upload_users->contains($user))
        {
            $this->upload_users->add($user);
        }

        return $this;
    }

    /**
     * Remove upload_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function removeUploadUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if($this->upload_users->contains($user))
        {
            $this->upload_users->removeElement($user);
        }

        return $this;
    }

    /**
     * Delete upload_users
     *
     * @return $this
     */
    public function resetUploadUsers()
    {
        $this->upload_users = new ArrayCollection();

        return $this;
    }

    /**
     * Set download_check
     *
     * @param boolean $download_check
     *
     * @return self
     */
    public function setDownloadCheck($download_check)
    {
        $this->download_check = $download_check;

        return $this;
    }

    /**
     * Get download_check
     *
     * @return boolean $download_check
     */
    public function getDownloadCheck()
    {
        return $this->download_check;
    }

    /**
     * Get download_users
     *
     * @return mixed
     */
    public function getDownloadUsers()
    {
        return $this->download_users;
    }

    /**
     * Set download_users
     *
     * @param ArrayCollection $users
     *
     * @return $this
     */
    public function setDownloadUsers(ArrayCollection $users)
    {
        $this->resetDownloadUsers();
        $this->download_users = $users;

        return $this;
    }

    /**
     * Add download_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function addDownloadUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if(!$this->download_users->contains($user))
        {
            $this->download_users->add($user);
        }

        return $this;
    }

    /**
     * Remove download_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function removeDownloadUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if($this->download_users->contains($user))
        {
            $this->download_users->removeElement($user);
        }

        return $this;
    }

    /**
     * Delete download_users
     *
     * @return $this
     */
    public function resetDownloadUsers()
    {
        $this->download_users = new ArrayCollection();

        return $this;
    }

    /**
     * Set comment_check
     *
     * @param boolean $comment_check
     *
     * @return self
     */
    public function setCommentCheck($comment_check)
    {
        $this->comment_check = $comment_check;

        return $this;
    }

    /**
     * Get comment_check
     *
     * @return boolean $comment_check
     */
    public function getCommentCheck()
    {
        return $this->comment_check;
    }

    /**
     * Get comment_users
     *
     * @return mixed
     */
    public function getCommentUsers()
    {
        return $this->comment_users;
    }

    /**
     * Set comment_users
     *
     * @param ArrayCollection $users
     *
     * @return $this
     */
    public function setCommentUsers(ArrayCollection $users)
    {
        $this->resetCommentUsers();
        $this->comment_users = $users;

        return $this;
    }

    /**
     * Add comment_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function addCommentUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if(!$this->comment_users->contains($user))
        {
            $this->comment_users->add($user);
        }

        return $this;
    }

    /**
     * Remove comment_users
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function removeCommentUsers(\Fhm\UserBundle\Entity\User $user)
    {
        if($this->comment_users->contains($user))
        {
            $this->comment_users->removeElement($user);
        }

        return $this;
    }

    /**
     * Delete comment_users
     *
     * @return $this
     */
    public function resetCommentUsers()
    {
        $this->comment_users = new ArrayCollection();

        return $this;
    }

    /**
     * Get tasks
     *
     * @return mixed
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set tasks
     *
     * @param ArrayCollection $tasks
     *
     * @return $this
     */
    public function setTasks(ArrayCollection $tasks)
    {
        $this->resetTasks();
        foreach($tasks as $task)
        {
            $task->setAction($this);
        }
        $this->tasks = $tasks;

        return $this;
    }

    /**
     * Add task
     *
     * @param \Fhm\WorkflowBundle\Entity\WorkflowTask $task
     *
     * @return $this
     */
    public function addTask(\Fhm\WorkflowBundle\Entity\WorkflowTask $task)
    {
        if(!$this->tasks->contains($task))
        {
            $this->tasks->add($task);
            $task->setAction($this);
        }

        return $this;
    }

    /**
     * Remove task
     *
     * @param \Fhm\WorkflowBundle\Entity\WorkflowTask $task
     *
     * @return $this
     */
    public function removeTask(\Fhm\WorkflowBundle\Entity\WorkflowTask $task)
    {
        if($this->tasks->contains($task))
        {
            $this->tasks->removeElement($task);
            $task->setAction(null);
        }

        return $this;
    }

    /**
     * Reset tasks
     *
     * @return $this
     */
    public function resetTasks()
    {
        foreach($this->tasks as $task)
        {
            $task->setAction(null);
        }
        $this->tasks = new ArrayCollection();

        return $this;
    }

    /**
     * User can validate
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return bool
     */
    public function hasUserValidate($user)
    {
        if($user instanceof \Fhm\UserBundle\Entity\User)
        {
            if($user->isSuperAdmin() || $user->hasRole('ROLE_ADMIN') || $this->validate_users->contains($user))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * User can dismiss
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return bool
     */
    public function hasUserDismiss($user)
    {
        if($user instanceof \Fhm\UserBundle\Entity\User)
        {
            if($user->isSuperAdmin() || $user->hasRole('ROLE_ADMIN') || $this->dismiss_users->contains($user))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * User can cancel
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return bool
     */
    public function hasUserCancel($user)
    {
        if($user instanceof \Fhm\UserBundle\Entity\User)
        {
            if($user->isSuperAdmin() || $user->hasRole('ROLE_ADMIN') || $this->cancel_users->contains($user))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * User can upload
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return bool
     */
    public function hasUserUpload($user)
    {
        if($user instanceof \Fhm\UserBundle\Entity\User)
        {
            if($user->isSuperAdmin() || $user->hasRole('ROLE_ADMIN') || $this->upload_users->contains($user))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * User can download
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return bool
     */
    public function hasUserDownload($user)
    {
        if($user instanceof \Fhm\UserBundle\Entity\User)
        {
            if($user->isSuperAdmin() || $user->hasRole('ROLE_ADMIN') || $this->download_users->contains($user))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * User can comment
     *
     * @param \Fhm\UserBundle\Entity\User $user
     *
     * @return bool
     */
    public function hasUserComment($user)
    {
        if($user instanceof \Fhm\UserBundle\Entity\User)
        {
            if($user->isSuperAdmin() || $user->hasRole('ROLE_ADMIN') || $this->comment_users->contains($user))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Sort update
     *
     * @return $this
     */
    public function sortUpdate()
    {
        $this->sort_task = $this->tasks->count();

        return parent::sortUpdate();
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->resetValidateUsers();
        $this->resetDismissUsers();
        $this->resetCancelUsers();
        $this->resetUploadUsers();
        $this->resetDownloadUsers();
        $this->resetCommentUsers();
        $this->resetTasks();

        return parent::preRemove();
    }
}
<li class="user-details">
    <div class="user-avatar">
        <img src="<?php echo get_user_avatar_url($this->userdata); ?>" class="rounded-circle img-fluid user-avatar-img" alt="<?php echo $this->userdata['username']; ?>"> </div>
    <div class="username font-lg text-center"><?php echo $this->userdata['username']; ?></div>
</li>
<?php
adminSidebarMap($this->page_sidebar_items);
?>
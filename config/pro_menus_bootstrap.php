<?php
    Croogo::hookBehavior('Link', 'ProMenus.ProLink', array());

    Croogo::hookComponent('*', 'ProMenus.ProMenus');

    Croogo::hookAdminTab('Links/admin_add', 'Pro', 'pro_menus.admin_tab_link');
    Croogo::hookAdminTab('Links/admin_edit', 'Pro', 'pro_menus.admin_tab_link');
?>
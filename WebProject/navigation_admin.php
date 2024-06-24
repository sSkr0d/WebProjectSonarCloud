<?php
    $current_page = basename($_SERVER['PHP_SELF']);
    
    $pages = array(
        'proposal_admin.php' => 'Event Proposal',
        'pmfki.php' => 'Manage PMFKI',
        'report_menu_admin.php' => 'Event Report',
        'signout.php' => 'Sign Out'
    );
    
    foreach ($pages as $page_link => $page_title) {
        $class = ($current_page == $page_link) ? 'active' : '';
        echo '<td><a href="' . $page_link . '" class="' . $class . '">' . $page_title . '</a></td>';
    }
?>

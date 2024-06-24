<?php
    $current_page = basename($_SERVER['PHP_SELF']);
    
    $pages = array(
        'proposal_pmfki.php' => 'Event Proposal',
        'event.php' => 'Event List',
        'report_menu.php' => 'Report',
        'signout.php' => 'Sign Out'
    );
    
    foreach ($pages as $page_link => $page_title) {
        $class = ($current_page == $page_link) ? 'active' : '';
        echo '<td><a href="' . $page_link . '" class="' . $class . '">' . $page_title . '</a></td>';
    }
?>

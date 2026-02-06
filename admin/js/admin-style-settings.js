jQuery(document).ready(function($) {
    'use strict';
    
    // Tab functionality
    $('.amrrev-style-settings-tabs .nav-tab').on('click', function(e) {
        e.preventDefault();
        
        var tabId = $(this).attr('href');
        
        // Hide all tabs
        $('.amrrev-style-tab-content').hide();
        
        // Remove active class from all tabs
        $('.nav-tab').removeClass('nav-tab-active');
        
        // Show selected tab
        $(tabId).show();
        
        // Add active class to clicked tab
        $(this).addClass('nav-tab-active');
    });
});
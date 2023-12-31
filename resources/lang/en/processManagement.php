<?php

return [

    'db' => [
        'low' => 'Low',
        'normal' => 'Normal',
        'high' => 'High',
        'urgent' => 'Urgent',
        'immediate' => 'Immediate',
        'pending' => 'Pending',
        'in_review' => 'In Review',
        'on_hold' => 'On Hold',
        'finished' => 'Finished',
        'administrative' => 'Administrative',
        'financial' => 'Financial',
        'legal' => 'Legal',
        'managerial' => 'Managerial',
        'business' => 'Business',
        'consulting' => 'Consulting',
        'commercial' => 'Commercial',
        'quality' => 'Quality',
        'compliance' => 'Compliance',
        'accounting' => 'Accounting',
        'others' => 'Others',
        'public' => 'public',
        'private' => 'Private',
        'processes_management' => 'Processes management',
        'administrator' => 'Administrator',
        'reading' => 'Reading',
        'writing' => 'Writing',
        'add_file' => 'Add file',
        'digitally_sign' => 'Digitally sign',
    ],

    'init' => [
        'processes_management' => 'Processes management',
        'my_processes' => 'My Processes',
        'register' => 'Register',
        'new_process' => 'New Process',
        'number' => 'Number',
        'name' => 'Name',
        'responsible' => 'Responsible',
        'created_in' => 'Created in',
        'type' => 'Type',
        'access' => 'access',
        'start_time' => 'Start time',
        'deadline' => 'Deadline',
        'priority' => 'Priority',
        'search' => 'Search',
        'public_consultation' => 'Public consultation',
        'new_consultation' => 'New consultation',
    ],

    'register' => [
        'process' => 'Process',
        'stage' => 'Stage',
        'processp_data' => 'Processp Data',
        'step_data' => 'Step data',
        'name' => 'Name',
        'description' => 'Description',
        'enter_the_name_of_the_process' => 'Enter the name of the process',
        'enter_the_process_description' => 'Enter the process description',
        'enter_the_start_date_of_the_process' => 'Enter the start date of the process',
        'enter_process_end_date' => 'Enter process end date',
        'enter_the_process_status' => 'Enter the process status',
        'enter_the_type_o_process' => 'Enter the type of process',
        'enter_the_type_of_process_access' => 'Enter the type of process access',
        'process_time' => 'Process Time',
        'enter_priority' => 'Enter priority',
        'priority' => 'Priority',
        'type' => 'Type',
        'access' => 'Access',
        'add_step' => 'Add step',
        'stage' => 'Stage',
        'enter_the_name_of_the_step' => 'Enter the name of the step',
        'enter_step_description' => 'Enter step description',
        'stage_start_time' => 'Stage start time',
        'enter_step_start_date' => 'Enter step start date',
        'stage_deadline' => 'Stage deadline',
        'enter_end_date_of_step' => 'Enter end date of step',
        'enter_step_type' => 'Enter end date of step',
        'enter_step_access_type' => 'Enter step access type',
        'stage_participants' => 'Stage participants',
        'participants' => 'Participants',
        'enter_the_process_number' => 'Enter the process number',
        'number' => 'Number',
        'save_where' => 'Save where',
        'folder' => 'Folder',
        'new' => 'New',
        'new_folder' => 'Choose which folder to save the document in, or click add to create a new folder.',
    ],

    'search' => [
        'number' => 'Número',
        'search' => 'Search',
        'name' => 'Name',
        'responsible' => 'Responsible',
        'creation_date' => 'Creation date',
        'start_date' => 'Start date',
        'end_date' => 'End date',
        'type' => 'Type',
        'access' => 'access',
        'priority' => 'Priority',
        'select' => 'Select',
        'cancel' => 'Cancel',
        'clear_filters' => 'Clear filters',
    ],

    'view' => [
        'process' => 'Process',
        'phases' => 'Phases',
        'number' => 'Number',
        'name' => 'Name',
        'created_in' => 'Created in',
        'name_of_responsible' => 'Name of responsible',
        'process_data' => 'Process data',
        'start_date' => 'Start date',
        'end_date' => 'End date',
        'access' => 'Access',
        'priority' => 'Priority',
        'type' => 'Type',
        'description' => 'Description',
        'change' => 'Change',
        'historic' => 'Historic',
        'message' => 'Message',
        'there_are_no_messages_or_interactions' => 'There are no messages or interactions',
        'there_are_no_participants' => 'There are no participants.',
        'drop_files_here_or_click_to_upload' => 'Drop files here or click to upload.',
        'participant_message' => 'Participant Message',
        'in' => 'in',
        'add_file' => 'Add file',
        'response' => 'Response',
        'delete' => 'Delete',
        'there_are_no_steps_at_this_time' => 'There are no steps at this time.',
        'add_phases' => 'Add phases',
        'add_participant' => 'Add participant',
        'files' => 'Files',
        'sorry' => 'Sorry',
        'this_file_type_cannot_be_played' => 'This file type cannot be played.',
        'add_file' => 'Add File',
        'import_files' => 'You cannot import files',
        'request' => 'request',
        'new_reload' => 'a new reload',
        'print' => 'Print process',
    ],

    'error' => [
        0 => 'Failed to spawn process',
        1 => 'Failed to fetch processes',
        2 => 'Process successfully saved!',
        3 => 'Process changed successfully!',
        4 => 'Error generating process! Verify that all required fields have been completed correctly.',
        5 => 'Error generating process! Verify that the step date is within the process period.',
        6 => "Failure! You can not attach files. Because the owner of the process didn't have enough credit. Ask the process owner to perform a new credit recharge.",
        7 => "Enter at least one parameter to perform the process search",
        8 => "No processes found",
    ],

    'public' => [
        'consult' => 'Public consultation of processes',
        'process_number' => 'Process number',
        'process_info' => 'Enter the process number.',
        'name' => 'Process Name',
        'process_name_info' => 'Enter the name of the process.',
        'responsible' => 'Responsible',
        'responsible_info' => 'Enter name of involved',
        'search' => 'Search',
    ],

];

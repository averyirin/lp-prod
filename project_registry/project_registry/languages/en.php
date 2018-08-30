<?php
/**
 * Projects Registry languages
 *
 * 
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'projects' => "Projects",
	'projects:all' => "Support Requests",
	'projects:create' => "Request Support",
	'projects:none' => "No projects",
	'projects:add' => "Support Request",
	'projects:searchname' => "Search for projects by name",
	'projects:search:name' => "project name",
	'projects:all:list' => "List Support Requests",

	/**
	 * Navigation
	 */
	'projects:label:all' => "All",
	'projects:label:mine' => "Mine",
	'projects:label:submitted' => "Submitted",
	'projects:label:underreview' => "Under Review",
	'projects:label:inprogress' => "In Progress",
	'projects:label:completed' => "Completed",
	'projects:label:onhold' => "On Hold",
	
	/**
	 * Sidebar
	 */
	'search:go' => "Go",
	'projects:filterByStatus' => "Filter By Status",
    'projects:filterByType' => "Filter By Type",
	
	/**
	 * Table of Contents
	 */
	'projects:toc' => "Table of Contents",
	
	/**
	 * Project Fields
	 */
	'projects:title'		=> 'Title',
	'projects:course'		=> 'Associated Course (Optional)',
	'projects:org'			=> 'Client Organization',
	'projects:type'			=> 'Type',
	'projects:description'	=> 'Description',
	'projects:description:helptext' => 'Describe in as much detail as possible the deficiency to be resolved or suggested improvement that triggered the request.',
	'projects:scope'		=> 'Scope',
	'projects:scope:helptext:header' => 'Explain:',
	'projects:scope:helptext:listItem1' => 'Does this pertain to a full course or a discrete portion of existing course',
	'projects:scope:helptext:listItem2' => 'The goal to be achieved',
	'projects:scope:helptext:listItem3' => 'Any specific objectives',
	'projects:scope:helptext:listItem4' => 'Major deliverables',
	'projects:scope:helptext:listItem5' => 'Project limitations',
	'projects:scope:helptext:listItem6' => 'Advantages to launching this request',
	'projects:ta'			=> 'Training Authority',
	'projects:opi'			=> 'Who is the project OPI for the Client? Provide contact information.',
	'projects:opi:helptext'	=> 'The OPI is the main point of contact for the entire project. They are required to be available for the duration of the project.',
	'projects:opi:title'	=> 'OPI',
	'projects:addContact'	=> 'Add OPI',
	'projects:removeContact'=> 'Remove OPI',
	'projects:isPriority'	=> 'Does the project line up with the Client\'s identified priorities?',
	'projects:priority:title' => 'Identified Priorites',
	'projects:briefExplain'	=> 'How does this support request support your organizations priorities or operational mandate?',
	'projects:briefExplain:helptext:header'	=> 'Examples of priorities could include:',
	'projects:briefExplain:helptext:listItem1'	=> 'CAF Campus priorities',
	'projects:briefExplain:helptext:listItem2'	=> 'Units’ priority to reduce training time or improve/change the delivery of a course or other priorities as outlined by the CMDT/CO.',
	'projects:briefExplain:helptext:listItem3'	=> 'MPG priorities such as:',
	'projects:briefExplain:helptext:subListItem1'	=> 'Anticipation of changing defence environment',
	'projects:briefExplain:helptext:subListItem2'	=> 'Continuous renewal of professional knowledge',
	'projects:briefExplain:helptext:subListItem3'	=> 'Efficient use of time',
	'projects:briefExplain:helptext:subListItem4'	=> 'Flexible and tailored learning models',
	'projects:briefExplain:helptext:subListItem5'	=> 'Best value for resources',
	'projects:isSmeAvail'	=> 'Will SME support be available for the duration of the project? If yes, please provide name and contact information?',
	'projects:isSme'		=> 'Will SME support be available for the duration of the project? If yes, please provide name and contact information?',
	'projects:sme'			=> 'Subject Matter Expert',
	'projects:isLimitation' => 'Are there any security, controlled goods or copyright issues or limitations with the content that would affect distribution?',
	'projects:isLimitation:title' => 'Controlled Goods/Copyright',
	'projects:updateExistingProduct' => 'Is this an update or change to an existing CAF/DND created product?',
	'projects:updateExistingProduct:title' => 'Existing CAF/DND product',
	'projects:lifeExpectancy'	=> 'What is the product/course life expectancy? A rough estimate is all that is required.',
	'projects:lifeExpectancy:title'	=> 'Product/Course Life Expectancy',
	'projects:usa'			=> 'Unit Signing Authority or Delegate',
	'projects:usa:title'	=> 'Unit Signing Authority',
	'projects:usa:helptext' => 'This individual could be the CO of a unit or CMDT / CI of a TE or any individual who has been delegated Financial/Logistical signing authority on behalf of above.',
	'projects:comments'		=> 'Comments',
	'projects:position'		=> 'Position',
	'projects:update'		=> 'Update',
	'projects:change'		=> 'Change',
	'projects:N/A'			=> 'N/A',
	'projects:rank'			=> 'Rank',
	'projects:name'			=> 'Name',
	'projects:phone'		=> 'Phone Number',
	'projects:email'		=> 'Email',
	'projects:status'		=> 'Status',
	'projects:submittedBy'	=> 'Submitted By',
	'projects:on'			=> 'on',
	'projects:reqNum'		=> 'Request #',
	'projects:files'		=> 'Attached Files',
	'projects:departmentOwner'		=> 'Department Owner',    
	'projects:dateSubmitted'=> 'Date Submitted',
	'projects:actions'		=> 'Actions',
	'projects:deleteConfirm'=> 'Are you sure you want to delete this project? There is no undo!',
	'projects:email:notification' => '(An email notification will be sent to this individual after your submission)',
	'projects:classification'	=> 'Project/Task',
	'projects:project'	=> 'Project',
	'projects:task'	=> 'Task',
	'projects:unassigned' => 'Unassigned',
	'projects:percentage'	=> 'Percentage Completed',
	'projects:checkmark:helper' => "Click the check mark above to save your changes",
	
	/**
	 * Form labels
	 */
	'projects:yes'		=> 'Yes',
	'projects:no'		=> 'No',
	'projects:submit'	=> 'Create Project',
	'projects:save'		=> 'Save Project',
    'projects:delete'   => 'Delete',
    'projects:edit'     => 'Edit',
    'projects:accept'   => 'Accept',
    'projects:cancel'   => 'Cancel',

	/**
	 * Support Types
	 */
	'projects:types:courseware' => 'Courseware',
    'projects:types:enterprise_apps' => 'Enterprise Learning Applications',
	'projects:types:instructor_support' => 'Instructor Support',
	'projects:types:learning_application' => 'Learning Application',
	'projects:types:learning_technologies' => 'Learning Technologies',
	'projects:types:mobile' => 'Mobile',
	'projects:types:modelling' => 'Modelling and Simulation',
	'projects:types:rnd' => 'R and D',
	'projects:types:gaming' => 'Serious Gaming',
	'projects:types:support' => 'Support',
	
	/*
	 * Department Owner 
	 */
	'projects:owner:learning_technologies' => 'Learning Technologies',
	'projects:owner:lsc' => 'Learning Support Centre',
	
	/**
	 * TA Options
	 */
	'projects:ta:air_force' => 'Air Force',
	'projects:ta:army' => 'Army',
	'projects:ta:mpc' => 'MPC',
	'projects:ta:navy' => 'Navy',
	
    /**
	 * Form input messages
	 */
    'projects:msg:required'	=> 'This field is required',
    'projects:msg:email'	=> 'This needs to be a valid email',
    'projects:msg:short'	=> 'This field is too short',
    'projects:msg:long'		=> 'This field is too long',

);

add_translation("en", $english);
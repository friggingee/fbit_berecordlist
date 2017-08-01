#
# Table structure for table 'tx_fbitberecordlist_domain_model_module'
#
CREATE TABLE tx_fbitberecordlist_domain_model_module (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	signature varchar(255) DEFAULT '' NOT NULL,
	icon varchar(255) DEFAULT '' NOT NULL,
	labels varchar(255) DEFAULT '' NOT NULL,
	storage_pid int(11) DEFAULT '0' NOT NULL,
	main_module varchar(255) DEFAULT '' NOT NULL,
	modulelayout_header_enabled smallint(5) unsigned DEFAULT '1' NOT NULL,
	modulelayout_header_menu_showoneoptionpertable smallint(5) unsigned DEFAULT '0' NOT NULL,
	modulelayout_header_menu_showoneoptionperrecordtype smallint(5) unsigned DEFAULT '0' NOT NULL,
	modulelayout_header_pagepath smallint(5) unsigned DEFAULT '1' NOT NULL,
	modulelayout_header_buttons_enabled smallint(5) unsigned DEFAULT '1' NOT NULL,
	modulelayout_header_buttons_showonenewrecordbuttonpertable smallint(5) unsigned DEFAULT '0' NOT NULL,
	modulelayout_header_buttons_showonenewrecordbuttonperrecordtype smallint(5) unsigned DEFAULT '0' NOT NULL,
	modulelayout_footer_enabled smallint(5) unsigned DEFAULT '1' NOT NULL,
	moduleylayout_footer_fieldselection smallint(5) unsigned DEFAULT '1' NOT NULL,
	modulelayout_footer_listoptions_extendedview smallint(5) unsigned DEFAULT '1' NOT NULL,
	modulelayout_footer_listoptions_clipboard smallint(5) unsigned DEFAULT '1' NOT NULL,
	modulelayout_footer_listoptions_localization smallint(5) unsigned DEFAULT '1' NOT NULL,
	tables int(11) unsigned DEFAULT '0' NOT NULL,
	modulelayout_header_buttons_left text,
	modulelayout_header_buttons_right text,
	modulelayout_header_buttons_left_override int(11) unsigned DEFAULT '0' NOT NULL,
	modulelayout_header_buttons_right_override int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state smallint(6) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),

);

#
# Table structure for table 'tx_fbitberecordlist_domain_model_table'
#
CREATE TABLE tx_fbitberecordlist_domain_model_table (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	module int(11) unsigned DEFAULT '0' NOT NULL,

	tablename varchar(255) DEFAULT '' NOT NULL,
	allowed_record_types varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state smallint(6) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),

);

#
# Table structure for table 'tx_fbitberecordlist_domain_model_button'
#
CREATE TABLE tx_fbitberecordlist_domain_model_button (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	module int(11) unsigned DEFAULT '0' NOT NULL,
	module2 int(11) unsigned DEFAULT '0' NOT NULL,

	identifier varchar(255) DEFAULT '' NOT NULL,
	override_identifier varchar(255) DEFAULT '' NOT NULL,
	header_side varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state smallint(6) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),

);

#
# Table structure for table 'tx_fbitberecordlist_domain_model_table'
#
CREATE TABLE tx_fbitberecordlist_domain_model_table (

	module int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_fbitberecordlist_domain_model_button'
#
CREATE TABLE tx_fbitberecordlist_domain_model_button (

	module int(11) unsigned DEFAULT '0' NOT NULL,

	module2 int(11) unsigned DEFAULT '0' NOT NULL,

);

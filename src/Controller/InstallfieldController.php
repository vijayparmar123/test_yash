<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;

Class installfieldController extends AppController
{

	public function install()
	{
		$this->autoRender = false;
		/*
		
		##attach_label and attach_file field added to erp_vendor
		##attach_label and attach_file field added to erp_inward_bill.
		##attach_label and attach_file field added to erp_inventory_grn.
		
		##last_edit and last_edit_By field added erp_vendor
		##last_edit and last_edit_by field added to erp_inventory_grn.
		##last_edit and last_edit_by field added to erp_inventory_is.
		##last_edit and last_edit_by field added to erp_inventory_rbn.
		##last_edit and last_edit_by field added to erp_projects.		
		##last_edit and last_edit_by field added to erp_inward_bill.
		##add 3 fields in table(create_by,last_edit_by,last_edited) erp_salary_slip
		##add 3 fields in db table(created_by,last_edit_by,last_edited) erp_leavesheet
		##add two field in db table(last_edit,last_edit_by) erp_users
		
		##show_in_purchase field added to erp_inventory_pr_material
		
		##credit_period field added to erp_inward_bill.
		
		##show_in_account field add to erp_inventory_grn
		##show_in_account field add to erp_inventory_mrn
		
		#tally_inward_no field added to erp_inward_bill
	
		
		#month and year field added to erp_salary_slip.
		
		
		#Created new field approved_for_grnwithoutpo in erp_inventory_pr_material
		
		##Changed difference_qty cloumn to text in erp_inventory_grn_detail
		
		##remarks field added to erp_inventory_grn.
	
		##branch_name field added to erp_vendor.
		##branch_name field added to erp_agency. */
		
		
		// die;
		$conn = ConnectionManager::get('default');		
		// $result = $conn->execute("ALTER TABLE `erp_drawing` ADD `last_edited_by` INT NULL DEFAULT NULL AFTER `created_date`, ADD `last_edit_date` DATE NULL DEFAULT NULL AFTER `last_edited_by`");
		// $result = $conn->execute("ALTER TABLE `erp_assets` ADD `last_edited_by` INT NULL DEFAULT NULL AFTER `due_date_insurance`, ADD `last_edit_date` DATE NULL DEFAULT NULL AFTER `last_edited_by`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_po` ADD `mail_check` TINYINT NOT NULL AFTER `bill_address`");
		// $result = $conn->execute("ALTER TABLE `erp_manual_po` CHANGE `remarks` `remarks` VARCHAR(15000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_po` CHANGE `remarks` `remarks` VARCHAR(15000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL");
		 // $result = $conn->execute("CREATE TABLE `erp_join_material_history` (
		  // `join_id` int(11) NOT NULL AUTO_INCREMENT,
		  // `material_code` int(11) DEFAULT NULL,
		  // `material_item_code` varchar(255) DEFAULT NULL,
		  // `material_title` varchar(255) DEFAULT NULL,
		  // `brand_id` int(11) DEFAULT NULL,
		  // `project_id` int(11) DEFAULT NULL,
		  // `unit_id` int(11) DEFAULT NULL,
		  // `description` text,
		  // `status` tinyint(4) NOT NULL,
		  // `material_id` int(11) DEFAULT NULL,
		  // `join_with_material` int(11) DEFAULT NULL,
		  // `join_by` int(11) DEFAULT NULL,
		  // `join_date` date DEFAULT NULL,
		  // PRIMARY KEY (`join_id`)
		// ) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_po` ADD `delivery_date` DATE NULL DEFAULT NULL AFTER `payment_method`");
		// $result = $conn->execute("ALTER TABLE `erp_manual_po` ADD `delivery_date` DATE NULL DEFAULT NULL AFTER `payment_method`");
		// $result = $conn->execute("ALTER TABLE `erp_work_order_detail` DROP `target_date`");
		// $result = $conn->execute("ALTER TABLE `erp_work_order` ADD `target_date` DATE NULL DEFAULT NULL AFTER `payment_method`");
		// $result = $conn->execute("ALTER TABLE `erp_work_order_detail` ADD `first_approved` TINYINT NOT NULL AFTER `amount`, ADD `first_approved_by` INT NULL DEFAULT NULL AFTER `first_approved`, ADD `first_approved_date`DATE NULL DEFAULT NULL AFTER `first_approved_by`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_po` ADD `custom_pan` VARCHAR(500) NULL DEFAULT NULL AFTER `vendor_address`, ADD `custom_gst` VARCHAR(500) NULL DEFAULT NULL AFTER `custom_pan`");
		// $result = $conn->execute("ALTER TABLE `erp_manual_po` ADD `custom_pan` VARCHAR(500) NULL DEFAULT NULL AFTER `vendor_address`, ADD `custom_gst` VARCHAR(500) NULL DEFAULT NULL AFTER `custom_pan`");
		// $result = $conn->execute("CREATE TABLE `erp_debit_note` (
		  // `debit_id` int(11) NOT NULL AUTO_INCREMENT,
		  // `project_id` int(11) DEFAULT NULL,
		  // `debit_note_no` varchar(255) DEFAULT NULL,
		  // `date` date DEFAULT NULL,
		  // `debit_to` varchar(255) DEFAULT NULL,
		  // `receiver_name` varchar(500) DEFAULT NULL,
		  // `attachment` varchar(500) DEFAULT NULL,
		  // `created_by` int(11) DEFAULT NULL,
		  // `created_date` date DEFAULT NULL,
		  // PRIMARY KEY (`debit_id`)
		// ) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		// $result = $conn->execute("CREATE TABLE `erp_debit_note_detail` (
		  // `detail_id` int(11) NOT NULL AUTO_INCREMENT,
		  // `debit_id` int(11) DEFAULT NULL,
		  // `reason` varchar(5000) DEFAULT NULL,
		  // `quantity` float DEFAULT NULL,
		  // `rate` float DEFAULT NULL,
		  // `amount` float DEFAULT NULL,
		  // `total_amount` float DEFAULT NULL,
		  // `total_word` varchar(1500) DEFAULT NULL,
		  // `first_approved` tinyint(4) NOT NULL,
		  // `first_approved_by` int(11) DEFAULT NULL,
		  // `first_approved_date` date DEFAULT NULL,
		  // `second_approved` tinyint(4) NOT NULL,
		  // `second_approved_by` int(11) DEFAULT NULL,
		  // `second_approved_date` date DEFAULT NULL,
		  // PRIMARY KEY (`detail_id`)
		// ) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_po_detail` CHANGE `delivery_date` `delivery_date` DATE NULL DEFAULT NULL");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_deleted_po_detail` CHANGE `delivery_date` `delivery_date` DATE NULL DEFAULT NULL");
		// $result = $conn->execute("ALTER TABLE `erp_users` ADD `pf_ref_no` VARCHAR(255) NULL DEFAULT NULL AFTER `date_of_birth`");
		// $result = $conn->execute("ALTER TABLE `erp_work_order` ADD `attachment` VARCHAR(5000) NULL DEFAULT NULL AFTER `remarks`");
		// $result = $conn->execute("ALTER TABLE `erp_inward_bill` ADD `checked_date` DATE NULL DEFAULT NULL AFTER `pending_approve_date`, ADD `checked_by` INT(11) NULL DEFAULT NULL AFTER `checked_date`");
		// $result = $conn->execute("ALTER TABLE `erp_work_order_detail` CHANGE `discount` `discount` FLOAT NULL DEFAULT NULL, CHANGE `cgst` `cgst` FLOAT NULL DEFAULT NULL, CHANGE `sgst` `sgst` FLOAT NULL DEFAULT NULL,CHANGE `igst` `igst` FLOAT NULL DEFAULT NULL");
		// $result = $conn->execute("ALTER TABLE `erp_users` ADD `paystructure_change_date` DATE NULL DEFAULT NULL AFTER `change_date`, ADD `paystructure_change_by` INT NULL DEFAULT NULL AFTER `paystructure_change_date`");
		// $result = $conn->execute("ALTER TABLE `erp_users` ADD `second_email` VARCHAR(50) NULL DEFAULT NULL AFTER `email_id`");
		// $result = $conn->execute("CREATE TABLE IF NOT EXISTS `erp_contract_notification` (
		  // `id` int(11) NOT NULL AUTO_INCREMENT,
		  // `project_id` int(11) NOT NULL,
		  // `project_code` varchar(255) DEFAULT NULL,
		  // `message` text NOT NULL,
		  // `event_date` date NOT NULL,
		  // `last_mailed_date` date DEFAULT NULL,
		  // `time_before` int(11) NOT NULL,
		  // `event_type` varchar(255) DEFAULT NULL,
		  // `updated_by` int(11) DEFAULT NULL,
		  // `updated_date` date DEFAULT NULL,
		  // `created_by` int(11) DEFAULT NULL,
		  // `created_date` date DEFAULT NULL,
		  // PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1");
		// $result = $conn->execute("CREATE TABLE IF NOT EXISTS `erp_maintainance_notification` (
		  // `id` int(11) NOT NULL AUTO_INCREMENT,
		  // `asset_id` int(11) NOT NULL,
		  // `asset_code` varchar(255) DEFAULT NULL,
		  // `asset_make` varchar(255) DEFAULT NULL,
		  // `asset_capacity` varchar(255) DEFAULT NULL,
		  // `model_no` varchar(255) DEFAULT NULL,
		  // `identity` varchar(255) DEFAULT NULL,
		  // `deploy_to` int(11) DEFAULT NULL,
		  // `message` text NOT NULL,
		  // `event_date` date NOT NULL,
		  // `last_mailed_date` date DEFAULT NULL,
		  // `time_before` int(11) NOT NULL,
		  // `event_type` varchar(255) DEFAULT NULL,
		  // `updated_by` int(11) DEFAULT NULL,
		  // `updated_date` date DEFAULT NULL,
		  // `created_by` int(11) DEFAULT NULL,
		  // `created_date` date DEFAULT NULL,
		  // PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1");
		// $result = $conn->execute("CREATE TABLE IF NOT EXISTS `erp_personal_notification` (
		  // `id` int(11) NOT NULL AUTO_INCREMENT,
		  // `message` text NOT NULL,
		  // `event_date` date NOT NULL,
		  // `last_mailed_date` date DEFAULT NULL,
		  // `time_before` int(11) NOT NULL,
		  // `event_type` varchar(255) DEFAULT NULL,
		  // `updated_by` int(11) DEFAULT NULL,
		  // `updated_date` date DEFAULT NULL,
		  // `created_by` int(11) DEFAULT NULL,
		  // `created_date` date DEFAULT NULL,
		  // PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1");
		// $result = $conn->execute("ALTER TABLE `erp_maintainance_notification` ADD `last_event_date` DATE NULL DEFAULT NULL AFTER `event_date`");
		// $result = $conn->execute("ALTER TABLE `erp_maintainance_notification` ADD `event_fixed_date` DATE NOT NULL AFTER `message`");
		 // $result = $conn->execute("CREATE TABLE IF NOT EXISTS `erp_sub_contract` (
		  // `id` int(11) NOT NULL AUTO_INCREMENT,
		  // `project_code` varchar(255) DEFAULT NULL,
		  // `project_id` int(11) NOT NULL,
		  // `type_of_bill` varchar(255) DEFAULT NULL,
		  // `yashnand_gst_no` varchar(255) DEFAULT NULL,
		  // `bill_mode` varchar(255) DEFAULT NULL,
		  // `bill_no` varchar(255) DEFAULT NULL,
		  // `bill_date` date DEFAULT NULL,
		  // `party_id` varchar(255) NOT NULL,
		  // `party_identy` varchar(255) DEFAULT NULL,
		  // `party_address` varchar(500) DEFAULT NULL,
		  // `party_no1` varchar(255) DEFAULT NULL,
		  // `party_no2` varchar(255) DEFAULT NULL,
		  // `party_pan_no` varchar(255) DEFAULT NULL,
		  // `party_gst_no` varchar(255) DEFAULT NULL,
		  // `bill_from_date` date DEFAULT NULL,
		  // `bill_to_date` date DEFAULT NULL,
		  // `type_of_work` text NOT NULL,
		  // `debit_this_bill` double DEFAULT NULL,
		  // `debit_previous_bill` double DEFAULT NULL,
		  // `debit_till_date` double DEFAULT NULL,
		  // `reconciliation_this_bill` double DEFAULT NULL,
		  // `reconciliation_previous_bill` double DEFAULT NULL,
		  // `reconciliation_till_date` double DEFAULT NULL,
		  // `sum_a` double DEFAULT NULL,
		  // `sum_b` double DEFAULT NULL,
		  // `sum_c` double DEFAULT NULL,
		  // `this_bill_amount` double DEFAULT NULL,
		  // `cgst_percentage` double DEFAULT NULL,
		  // `cgst` double DEFAULT NULL,
		  // `sgst_percentage` double DEFAULT NULL,
		  // `sgst` double DEFAULT NULL,
		  // `igst_percentage` double DEFAULT NULL,
		  // `igst` double DEFAULT NULL,
		  // `gross_amount` double DEFAULT NULL,
		  // `retention_percentage` double DEFAULT NULL,
		  // `retention_money` double DEFAULT NULL,
		  // `net_amount` double DEFAULT NULL,
		  // `attachment` varchar(1500) DEFAULT NULL,
		  // `first_approval` tinyint(4) NOT NULL DEFAULT '0',
		  // `first_approval_by` int(11) DEFAULT NULL,
		  // `first_approval_date` date DEFAULT NULL,
		  // `approval` tinyint(4) NOT NULL DEFAULT '0',
		  // `approval_by` int(11) DEFAULT NULL,
		  // `approval_date` date DEFAULT NULL,
		  // `created_by` int(11) DEFAULT NULL,
		  // `created_date` date DEFAULT NULL,
		  // `updated_by` int(11) DEFAULT NULL,
		  // `updated_date` date DEFAULT NULL,
		  // PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1");
		// $result = $conn->execute("CREATE TABLE IF NOT EXISTS `erp_sub_contract_detail` (
		  // `id` int(11) NOT NULL AUTO_INCREMENT,
		  // `sub_contract_id` int(11) NOT NULL,
		  // `item_no` varchar(255) DEFAULT NULL,
		  // `description` text,
		  // `unit` varchar(255) DEFAULT NULL,
		  // `quantity_this_bill` double DEFAULT NULL,
		  // `quantity_previous_bill` double DEFAULT NULL,
		  // `quantity_till_date` double DEFAULT NULL,
		  // `rate` double DEFAULT NULL,
		  // `amount_this_bill` double DEFAULT NULL,
		  // `amount_previous_bill` double DEFAULT NULL,
		  // `amount_till_date` double DEFAULT NULL,
		  // `first_approve` tinyint(4) NOT NULL DEFAULT '0',
		  // `first_approve_by` int(11) DEFAULT NULL,
		  // `first_approve_date` date DEFAULT NULL,
		  // `approval` tinyint(4) NOT NULL DEFAULT '0',
		  // `approval_by` int(11) DEFAULT NULL,
		  // `approval_date` date DEFAULT NULL,
		  // PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1");
		// $result = $conn->execute("ALTER TABLE `erp_users` ADD `employee_address` TEXT NULL DEFAULT NULL AFTER `mobile_no`");
		// $result = $conn->execute("ALTER TABLE `erp_users` ADD `designation_change_by` INT NULL DEFAULT NULL AFTER `paystructure_change_by`, ADD `designation_change_date` DATE NULL DEFAULT NULL AFTER`designation_change_by`");
		// $result = $conn->execute("ALTER TABLE `erp_users` ADD `is_change_designation` TINYINT NOT NULL DEFAULT '0' AFTER `paystructure_change_by`");
		// $result = $conn->execute("ALTER TABLE `erp_users` ADD `actual_designation_change_date` DATE NULL DEFAULT NULL AFTER `designation_change_by`");
		// $result = $conn->execute("CREATE TABLE IF NOT EXISTS `erp_designation_history` (
		  // `id` int(11) NOT NULL AUTO_INCREMENT,
		  // `user_id` int(11) NOT NULL,
		  // `change_date` date NOT NULL,
		  // `old_date` date DEFAULT NULL,
		  // `designation` bigint(20) DEFAULT NULL,
		  // `created_date` date DEFAULT NULL,
		  // `created_by` int(11) DEFAULT NULL,
		  // `updated_date` date DEFAULT NULL,
		  // `updated_by` int(11) DEFAULT NULL,
		  // PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM AUTO_INCREMENT=2082 DEFAULT CHARSET=latin1");
		// $result = $conn->execute("ALTER TABLE `erp_users` ADD `aadhar_card_att` VARCHAR(500) NULL DEFAULT NULL AFTER `attachment`, ADD `pan_card_att` VARCHAR(500) NULL DEFAULT NULL AFTER `aadhar_card_att`, ADD`driving_licence_att` VARCHAR(500) NULL DEFAULT NULL AFTER `pan_card_att`, ADD `cancel_cheque_att` VARCHAR(500) NULL DEFAULT NULL AFTER `driving_licence_att`, ADD `resume_att`VARCHAR(500) NULL DEFAULT NULL AFTER `cancel_cheque_att`, ADD `qualification_doc` VARCHAR(500) NULL DEFAULT NULL AFTER `resume_att`, ADD `other_doc` VARCHAR(500) NULL DEFAULT NULL AFTER`qualification_doc`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_grn` CHANGE `grn_no` `grn_no` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `grn_time` `grn_time` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `vendor_id` `vendor_id` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `payment_method` `payment_method` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `challan_no` `challan_no` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `vehicle_no` `vehicle_no` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `vouchar_no` `vouchar_no` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL, CHANGE `challan_bill` `challan_bill` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL");
		// $result = $conn->execute("ALTER TABLE `erp_material` ADD `consume` TINYINT NOT NULL DEFAULT '1' AFTER `unit_id`");
		// $result = $conn->execute("ALTER TABLE `erp_sub_contract` ADD `party_type` VARCHAR(200) NULL DEFAULT NULL AFTER `bill_date`");
		// $result = $conn->execute("CREATE TABLE IF NOT EXISTS `erp_vendor_groups` (
		  // `id` int(11) NOT NULL AUTO_INCREMENT,
		  // `code` varchar(255) DEFAULT NULL,
		  // `title` varchar(255) DEFAULT NULL,
		  // `created_at` varchar(255) DEFAULT NULL,
		  // `created_by` int(11) DEFAULT NULL,
		  // `updated_at` varchar(255) DEFAULT NULL,
		  // `updated_by` int(11) DEFAULT NULL,
		  // PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1");
		// $result = $conn->execute("INSERT INTO `erp_vendor_groups` (`id`, `code`, `title`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
		// (1, 'LC', 'Loose Cement', NULL, NULL, NULL, NULL),
		// (2, 'PC1', 'Packed Cement1', NULL, NULL, NULL, NULL),
		// (3, 'ST', 'Steel', NULL, NULL, NULL, NULL),
		// (4, 'CV', 'Civil', NULL, NULL, NULL, NULL),
		// (5, 'PL', 'Plumbing, Drainage & Sanitory', NULL, NULL, NULL, NULL),
		// (6, 'EC', 'Electric', NULL, NULL, NULL, NULL),
		// (7, 'EL', 'Electronic', NULL, NULL, NULL, NULL),
		// (8, 'SP', 'Spares', NULL, NULL, NULL, NULL),
		// (9, 'AS', 'Hardware', NULL, NULL, NULL, NULL),
		// (10, 'HV', 'HVAC', NULL, NULL, NULL, NULL),
		// (11, 'FF', 'Fire Fighting', NULL, NULL, NULL, NULL),
		// (12, 'IN', 'Interior', NULL, NULL, NULL, NULL),
		// (13, 'DS', 'Fuel', NULL, NULL, NULL, NULL),
		// (14, 'SF', 'Safety', NULL, NULL, NULL, NULL),
		// (15, 'OT', 'Others', NULL, NULL, NULL, NULL),
		// (17, 'TEMP', 'Temp', NULL, NULL, NULL, NULL)");
		// $result = $conn->execute("CREATE TABLE IF NOT EXISTS `erp_asset_groups` (
		  // `id` int(11) NOT NULL AUTO_INCREMENT,
		  // `code` varchar(255) DEFAULT NULL,
		  // `title` varchar(255) DEFAULT NULL,
		  // `created_at` varchar(255) DEFAULT NULL,
		  // `created_by` int(11) DEFAULT NULL,
		  // `updated_at` varchar(255) DEFAULT NULL,
		  // `updated_by` int(11) DEFAULT NULL,
		  // PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1");
		// $result = $conn->execute("INSERT INTO `erp_asset_groups` (`id`, `code`, `title`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
		// (1, 'PL', 'Plant', NULL, NULL, NULL, NULL),
		// (2, 'MA', 'Machine', NULL, NULL, NULL, NULL),
		// (3, 'HV', 'Heavy Vehicle', NULL, NULL, NULL, NULL),
		// (4, 'SV', 'Small Vehicle', NULL, NULL, NULL, NULL),
		// (5, 'EQ', 'Shuttering', NULL, NULL, NULL, NULL),
		// (6, 'FR', 'Furniture', NULL, NULL, NULL, NULL),
		// (7, 'EL', 'Electronics', NULL, NULL, NULL, NULL),
		// (8, 'TL', 'Tools', NULL, NULL, NULL, NULL),
		// (9, 'OT', 'Others', NULL, NULL, NULL, NULL),
		// (10, 'EC', 'Electric', NULL, NULL, NULL, NULL)");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_pr_material` ADD `po_pending_quantity` FLOAT NULL DEFAULT NULL AFTER `approved`, ADD `po_approved_quantity` FLOAT NULL DEFAULT NULL AFTER`po_pending_quantity`, ADD `po_completed` TINYINT NOT NULL DEFAULT '0' AFTER `po_approved_quantity`");
		// $result = $conn->execute("ALTER TABLE `erp_material` ADD `cost_group` VARCHAR(255) NOT NULL DEFAULT 'c' AFTER `consume`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_grn` ADD `challan_date` DATE NULL DEFAULT NULL AFTER `challan_no`, ADD `security_gate_pass_no` VARCHAR(255) NULL DEFAULT NULL AFTER `challan_date`, ADD`gate_pass_date` DATE NULL DEFAULT NULL AFTER `security_gate_pass_no`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_po_detail` ADD `grn_remain_qty` FLOAT NOT NULL DEFAULT '0' AFTER `quantity`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_grn_detail` ADD `po_detail_id` INT NOT NULL DEFAULT '0' AFTER `grn_id`");
		// $result = $conn->execute("ALTER TABLE `erp_assets` CHANGE `insurance_company` `insurance_company` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL");
		// $result = $conn->execute("ALTER TABLE `erp_assets` CHANGE `due_date_insurance` `due_date_insurance` DATE NULL DEFAULT NULL");
		// $result = $conn->execute("ALTER TABLE `erp_assets` CHANGE `due_date_reg` `due_date_reg` DATE NULL DEFAULT NULL");
		// $result = $conn->execute("ALTER TABLE `erp_assets` ADD `passing_registration_status` TINYINT NOT NULL AFTER `operational_status`");
		// $result = $conn->execute("ALTER TABLE `erp_assets` ADD `insurance_status` TINYINT NOT NULL AFTER `insurance_company`");
		// $result = $conn->execute("ALTER TABLE `erp_assets` ADD `road_tax_status` TINYINT NOT NULL AFTER `due_date_insurance`, ADD `due_date_road_tax` VARCHAR(500) NULL DEFAULT NULL AFTER `road_tax_status`, ADD `fitness_status` TINYINT NOT NULL AFTER `due_date_road_tax`, ADD `due_date_fitness` VARCHAR(500) NULL DEFAULT NULL AFTER `fitness_status`");
		// $result = $conn->execute("ALTER TABLE `erp_assets` CHANGE `operational_status` `operational_status` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL");
		// $result = $conn->execute("ALTER TABLE `erp_assets_history` ADD `remarks` VARCHAR(500) NULL DEFAULT NULL AFTER `accepted`, ADD `accept_date` DATE NULL DEFAULT NULL AFTER `remarks`, ADD `release_date` DATE NULL DEFAULT NULL AFTER `accept_date`, ADD `updated_date` DATE NULL DEFAULT NULL AFTER `release_date`, ADD `updated_by` INT NULL DEFAULT NULL AFTER `updated_date`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_pr_material` ADD `usages` TEXT NULL DEFAULT NULL AFTER `name_of_subcontractor`");
		// $result = $conn->execute("ALTER TABLE `erp_manual_po` ADD `related_grn_id` INT NULL DEFAULT NULL AFTER `mail_check`");
		// $result = $conn->execute("ALTER TABLE `erp_manual_po` CHANGE `related_grn_id` `related_grn_id` INT(11) NULL DEFAULT '0'");
		// $result = $conn->execute("ALTER TABLE `erp_manual_po` ADD `is_grn_base` TINYINT NOT NULL DEFAULT '0' AFTER `mail_check`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_grn` ADD `manualpo_id` TINYINT NOT NULL DEFAULT '0' AFTER `po_id`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_grn` CHANGE `manualpo_id` `manualpo_no` TINYINT(4) NOT NULL DEFAULT '0'");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_grn` ADD `local_po_id` TINYINT NOT NULL DEFAULT '0' AFTER `manualpo_no`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_grn` CHANGE `local_po_id` `local_po_id` INT(4) NOT NULL DEFAULT '0'");
		// $result = $conn->execute("CREATE TABLE IF NOT EXISTS `erp_asset_booking_history` (
		  // `id` int(11) NOT NULL AUTO_INCREMENT,
		  // `asset_id` int(11) DEFAULT NULL,
		  // `project_id` int(11) DEFAULT NULL,
		  // `requirment_date` date DEFAULT NULL,
		  // `entry_date` date DEFAULT NULL,
		  // `created_by` int(11) DEFAULT NULL,
		  // `created_date` date DEFAULT NULL,
		  // `updated_by` int(11) DEFAULT NULL,
		  // `updated_date` date DEFAULT NULL,
		  // PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1");
		// $result = $conn->execute("CREATE TABLE IF NOT EXISTS `erp_asset_issued_history` (
		  // `id` int(11) NOT NULL AUTO_INCREMENT,
		  // `asset_id` int(11) NOT NULL,
		  // `project_id` int(11) DEFAULT NULL,
		  // `issued_to` varchar(500) DEFAULT NULL,
		  // `issued_date` date DEFAULT NULL,
		  // `return_date` date DEFAULT NULL,
		  // `created_by` int(11) DEFAULT NULL,
		  // `created_date` date DEFAULT NULL,
		  // `updated_by` int(11) DEFAULT NULL,
		  // `updated_date` date DEFAULT NULL,
		  // PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1");
		// $result = $conn->execute("ALTER TABLE `erp_assets_maintenance` ADD `maintenance_type` INT NOT NULL DEFAULT '1' AFTER `vehicle_no`, ADD `party_name` VARCHAR(500) NULL DEFAULT NULL AFTER `maintenance_type`");
		// $result = $conn->execute("CREATE TABLE IF NOT EXISTS `erp_equipmentown_log` (
		  // `id` int(11) NOT NULL AUTO_INCREMENT,
		  // `el_no` varchar(500) DEFAULT NULL,
		  // `project_id` int(11) DEFAULT NULL,
		  // `date` date DEFAULT NULL,
		  // `ownership` varchar(255) DEFAULT NULL,
		  // `asset_group_id` int(11) DEFAULT NULL,
		  // `asset_code` varchar(500) DEFAULT NULL,
		  // `asset_id` int(11) DEFAULT NULL,
		  // `asset_make` varchar(500) DEFAULT NULL,
		  // `asset_capacity` varchar(500) DEFAULT NULL,
		  // `asset_model` varchar(500) DEFAULT NULL,
		  // `asset_identity` varchar(500) DEFAULT NULL,
		  // `working_status` varchar(255) DEFAULT NULL,
		  // `duty_time` double NOT NULL,
		  // `breakdown_time` double NOT NULL,
		  // `start_km` double NOT NULL,
		  // `stop_km` double NOT NULL,
		  // `usage_km` double NOT NULL,
		  // `start_hr` double NOT NULL,
		  // `stop_hr` double NOT NULL,
		  // `usage_hr` double NOT NULL,
		  // `driver_name` varchar(500) NOT NULL,
		  // `usage_detail` text NOT NULL,
		  // `approved_by` int(11) DEFAULT NULL,
		  // `crated_by` int(11) DEFAULT NULL,
		  // `created_date` date DEFAULT NULL,
		  // `updated_by` int(11) DEFAULT NULL,
		  // `updated_date` date DEFAULT NULL,
		  // PRIMARY KEY (`id`)
		// ) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1");
		// $result = $conn->execute("ALTER TABLE `erp_assets_sold_history` ADD `deployed_to` INT NULL DEFAULT NULL AFTER `asset_id`");
		// $result = $conn->execute("ALTER TABLE `erp_assets_theft_history` ADD `deployed_to` INT NULL DEFAULT NULL AFTER `asset_id`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_purhcase_request` ADD `attach_label` VARCHAR(5000) NULL DEFAULT NULL AFTER `is_manual`, ADD `attach_file` VARCHAR(5000) NULL DEFAULT NULL AFTER `attach_label`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_pr_material` ADD `purchase_remarks` TEXT NULL DEFAULT NULL AFTER `usages`");
		// $result = $conn->execute("ALTER TABLE `erp_inventory_pr_material` ADD `done_remarks` TEXT NULL DEFAULT NULL AFTER `purchase_remarks`");
		// $result = $conn->execute("ALTER TABLE `erp_work_order` ADD `mail_check` TINYINT NOT NULL DEFAULT '1' AFTER `attachment`");
		die("installed,PL. Remove code");
		
	}
	
	
	public function show()
	{
		$this->autoRender = false;
		$conn = ConnectionManager::get('default');		
		 $result = $conn->execute('SHOW COLUMNS FROM erp_work_order');	
		// $result = $conn->execute('SELECT * FROM erp_assets');	
		debug($result->fetchAll("assoc"));
		// debug($result);
		// die;
	}
	
	public function sendmail()
	{
		$this->autoRender = false;
		$headers = "das@dasinfomedia.com";
		$email_to = 'vijay.parmar@dasinfomedia.com,manan.patel@yashnandeng.com';
		$email_subject = 'Test Message';
		$email_message = 'This is for just test mail';
		// $email = mail($email_to, $email_subject, $email_message, $headers);
		$email = new Email('default');
						  $email->from("das@gmail.com")
						 ->to(['vijay.parmar@dasinfomedia.com'])
						 ->subject($email_subject)
						 ->send($email_message);
		if($email)
		{
			echo "Mail send Successfully";
		}
		else
		{
			echo "Any thing problem with Send Mail";
		}
	}
	
	// public function designation()
	// {
		// $history_tbl = TableRegistry::get("erp_users_history");
		// $history_data = $history_tbl->find()->hydrate(false)->toArray();
		
		// foreach($history_data as $history)
		// {
			// $user_id = $history['user_id'];
			// $change_date = $history['change_date'];
			// $old_date = $history['old_date'];
			// $designation = $history['designation'];
			// $created_date = $history['created_date'];
			// $created_by = $history['creaded_by'];
			// $updated_date = $history['last_edit'];
			// $updated_by = $history['last_edit_by'];
			
			// $erp_designation_history = TableRegistry::get("erp_designation_history");
			// $d_row = $erp_designation_history->newEntity();
			// $d_row['user_id'] = $user_id;
			// $d_row['change_date'] = date("Y-m-d",strtotime($change_date));
			// $d_row['old_date'] = date("Y-m-d",strtotime($old_date));
			// $d_row['designation'] = $designation;
			// $d_row['created_date'] = date("Y-m-d",strtotime($created_date));
			// $d_row['created_by'] = $created_by;
			// $d_row['updated_date'] = date("Y-m-d",strtotime($updated_date));
			// $d_row['updated_by'] = $updated_by;
			
			// $erp_designation_history->save($d_row);	
		// }
		// $erp_designation_history = TableRegistry::get("erp_designation_history");
		// $designation_data = $erp_designation_history->find();
		// debug($designation_data->count());
		// echo "below is history records";
		// $history_records = $history_tbl->find();
		// debug($history_records->count());die;
	// }
	
	// public function userchangedate()
	// {
		// $erp_users = TableRegistry::get("erp_users");
		// $user_data = $erp_users->find()->hydrate(false)->toArray();
		// $i = 0;
		// foreach($user_data as $user)
		// {
			// $is_paystructure_change = $user['is_pay_structure_change'];
			// if($user['change_date'] != null)
			// {
				// $paystructure_change_date = date("Y-m-d",strtotime($user['change_date']));
			// }else{
				// $paystructure_change_date = $user['change_date'];
			// }
			
			// if($user['paystructure_change_date'] != null)
			// {
				// $actual_paystructure_change_date = date("Y-m-d",strtotime($user['paystructure_change_date']));
			// }else{
				// $actual_paystructure_change_date = $user['paystructure_change_date'];
			// }
			
			// $paystructure_change_by = $user['paystructure_change_by'];
			
			// $current_user = $erp_users->get($user['user_id']);
			// $current_user->is_change_designation = $is_paystructure_change;
			// $current_user->designation_change_by = $paystructure_change_by;
			// $current_user->actual_designation_change_date = $actual_paystructure_change_date;
			// $current_user->designation_change_date = $paystructure_change_date;
			// $ok = $erp_users->save($current_user);
			// $i++;
		// }
		// debug($i);die;
	// }
	
	// public function prchangeapproveby()
	// {	
		// $pr_tbl = TableRegistry::get("erp_inventory_purhcase_request");
		// $mat_tbl = TableRegistry::get("erp_inventory_pr_material");
		// $pr_data  = $mat_tbl->find()->select($mat_tbl);
		// $pr_data = $pr_data->leftjoin(["erp_inventory_purhcase_request"=>"erp_inventory_purhcase_request"],
								  // ["erp_inventory_pr_material.pr_id = erp_inventory_purhcase_request.pr_id"])
								  // ->select($pr_tbl)->hydrate(false)->toArray();
		
		
		// foreach($pr_data as $retrive_data)
		// {
			// $retrive_data = array_merge($retrive_data,$retrive_data["erp_inventory_purhcase_request"]);
			// $project_id = $retrive_data['project_id'];
			
			// ##########################	
			// $asg_tbl = TableRegistry::get("erp_projects_assign");
			// $project_users = $asg_tbl->find("list",["keyField"=>"autoid","valueField"=>"user_id"])->where(["project_id"=>$project_id])->toArray();
			
			
			// if(!empty($project_users))
			// {  
				// $user_tbl = TableRegistry::get("erp_users");
				// $material_managers = $user_tbl->find()->where(["user_id IN"=>$project_users,"status !="=>0,"OR"=>[["role"=>"materialmanager"]]])->select(["user_id"])->hydrate(false)->toArray();
				
				// if(count($material_managers))
				// {
					// $row = $pr_tbl->get($retrive_data['pr_id']);
					// $row->created_by = $material_managers[0]['user_id'];
					// $pr_tbl->save($row);
				// }
				
				
				// $constructionmanagers = $user_tbl->find()->where(["user_id IN"=>$project_users,"status !="=>0,"OR"=>[["role"=>"constructionmanager"]]])->select(["user_id"])->hydrate(false)->toArray();
				
				
				// if(count($constructionmanagers))
				// {
					// $record = $mat_tbl->get($retrive_data['pr_material_id']);
					// $record->approved_by = $constructionmanagers[0]['user_id'];
					// $mat_tbl->save($record);
				// }
				
			// }
			
		// }
		// echo "Done";
		// die;
	// }
		
	// public function materialcodechange()
	// {
		// $erp_material = TableRegistry::get("erp_material");
		// $materials = $erp_material->find()->where(["project_id"=>0])->hydrate(false)->toArray();
		
		// $number = '000000000';
		// foreach($materials as $material)
		// {
			// $row = $erp_material->get($material['material_id']);
			// $code = $row['material_item_code'];
			
			// $c = explode("/",$code);
			// $number = (int) $number;
			// $c4 = str_pad(++$number,9,'0',STR_PAD_LEFT);
			// $new_code = $c[0].'/'.$c[1].'/'.$c[2].'/'.$c4;
				
			// $row['material_item_code'] = $new_code;
			// $erp_material->save($row);
		// }
		// die;
	// }
	
	// public function setnullprojectmaterialcode()
	// {
		// $erp_material = TableRegistry::get("erp_material");
		// $query = $erp_material->query();
		// $ok = $query->update()
		// ->set(['material_item_code'=>''])
		// ->where(["project_id >"=>0])
		// ->execute();
		// die;
	// }
	// public function projectmaterialcodechange()
	// {
		// $erp_material = TableRegistry::get("erp_material");
					
		// $materials = $erp_material->find()->where(["project_id >"=>0,"change_done"=>0])->select(["material_id"])->limit(200)->hydrate(false)->toArray();
		// if(!empty($materials))
		// {
			// foreach($materials as $material)
			// {
				// $row = $erp_material->get($material['material_id']);
				// $project_id = $row['project_id'];
				
				// /* Get Next Sequence Number */
				// $seq_no = $this->ERPfunction->generate_auto_id($project_id,"erp_material","material_id","material_item_code");
				// $seq_no = sprintf("%09d", $seq_no);
				// /* Get Next Sequence Number */
				
				// /* Get Project Number */
				// $project_code = $this->ERPfunction->get_projectcode($project_id);
				// $c = explode("/",$project_code);
				// $project_code_number = ($c[2])?$c[2]:"000";
				// $new_code = "YNEC/MT/TMP/{$project_code_number}/{$seq_no}";
				// /* Get Project Number */
				
				// $row['material_item_code'] = $new_code;
				// $row['change_done'] = 1;
				// $erp_material->save($row);
			// }
		// }else{
			// echo "Done";die;
		// }
		// die;
	// }
	
	// public function projectdataremove()
	// {
		// PR Data Remove
		/* $erp_inventory_purhcase_request = TableRegistry::get("erp_inventory_purhcase_request");
		$erp_inventory_pr_material = TableRegistry::get("erp_inventory_pr_material");
		
		$pr_id = $erp_inventory_purhcase_request->find()->select("pr_id")->where(['project_id'=>21])->hydrate(false)->toArray();
		
		$new_array = array();
		foreach($pr_id as $value) { $new_array[] = $value['pr_id']; }
		
		$delete_data = $erp_inventory_pr_material->deleteAll(['pr_id IN' => $new_array]);
		
		if($delete_data)
		{
			$delete = $erp_inventory_purhcase_request->deleteAll(['pr_id IN' => $new_array]);
			if($delete)
			{
				echo "Ok";die;
			}
		} */
		
		#################################################################################################
		
		// GRN Data Remove
		/* $erp_inventory_grn = TableRegistry::get("erp_inventory_grn");
		$erp_inventory_grn_detail = TableRegistry::get("erp_inventory_grn_detail");
		
		$grn_id = $erp_inventory_grn->find()->select("grn_id")->where(['project_id'=>21])->hydrate(false)->toArray();
		
		$new_array = array();
		foreach($grn_id as $value) { $new_array[] = $value['grn_id']; }
		
		$delete_data = $erp_inventory_grn_detail->deleteAll(['grn_id IN' => $new_array]);
		
		if($delete_data)
		{
			$delete = $erp_inventory_grn->deleteAll(['grn_id IN' => $new_array]);
			if($delete)
			{
				echo "Ok";die;
			}
		} */ 
		
		#################################################################################################
		
		// IS Data Remove
		/* $erp_inventory_is = TableRegistry::get("erp_inventory_is");
		$erp_inventory_is_detail = TableRegistry::get("erp_inventory_is_detail");
		
		$is_id = $erp_inventory_is->find()->select("is_id")->where(['project_id'=>21])->hydrate(false)->toArray();
		
		$new_array = array();
		foreach($is_id as $value) { $new_array[] = $value['is_id']; }
		
		$delete_data = $erp_inventory_is_detail->deleteAll(['is_id IN' => $new_array]);
		
		if($delete_data)
		{
			$delete = $erp_inventory_is->deleteAll(['is_id IN' => $new_array]);
			if($delete)
			{
				echo "Ok";die;
			}
		} */
		
		#################################################################################################
		
		// Stock table Data Remove
		/* $erp_stock_history = TableRegistry::get("erp_stock_history");
		
		$delete = $erp_stock_history->deleteAll(['project_id' => 21]);
		if($delete)
		{
			echo "Ok";die;
		}  */
		
		#################################################################################################
		
		// Project material Data Remove
		/* $erp_material = TableRegistry::get("erp_material");
		
		$delete = $erp_material->deleteAll(['project_id' => 21]);
		if($delete)
		{
			echo "Ok";die;
		} */  
	// }
	
	// public function approveinwardbills()
	// {
		// $table_register_inward_bill=TableRegistry::get('erp_inward_bill');
		//// $status = "'pending','checked'";
		// $records = $table_register_inward_bill->find()->where(["date < "=>"2018-08-15","status_inward !="=>"completed"])->select(["inward_bill_id","date"])->hydrate(false)->toArray();
		
		// foreach($records as $req_id)
		// {
			// if($req_id['inward_bill_id'] != '')
			// {
				// $user_create=$this->request->session()->read('user_id');
				// date_default_timezone_set('asia/kolkata');
				// $date=date('Y-m-d H:i:s');
				// $row = $table_register_inward_bill->get($req_id['inward_bill_id']);
				// $row['status_inward'] = 'completed';
				// $row['accept_date'] = $date;
				// $row['accept_by'] = '478';
				// $check=$table_register_inward_bill->save($row);
			// }
		// }
		// echo "success";die;
	// }
	
	// public function transactioncheck()
	// {
		// $erp_temporary=TableRegistry::get('erp_temporary');
		// $query = $erp_temporary->query();
		// $query->update()
		// ->set(['OLD_Code'=>'oldcode1'])
		// ->where(['OLD_Code' => 'oldcode'])
		// ->execute();
		
		// $query1 = $erp_temporary->query();
		// $query1->update()
		// ->set(['Unit'=>'kg'])
		// ->where(['OLD_Code' => 'oldcode1'])
		// ->execute();
		// var_dump($query);die;
		
		// $connection = ConnectionManager::get('default');
		// try{
			// $connection->begin();
			// $stmt = $connection->execute(
			// "UPDATE erp_temporary SET OLD_Code = 'oldcode1' WHERE OLD_Code = 'oldcode'"
			// );
			
			// $stmt1 = $connection->execute(
			// "UPDATE erp_temporary SET Unit123 = 'KG1' WHERE OLD_Code = 'oldcode1'"
			// );
			
			// $connection->commit();
		// }catch(Exception $e){
			// $connection->rollback();
			// var_dump($e);die;
		// }
	// }
	
	// public function assetcodechange()
	// {
		// $erp_assets = TableRegistry::get("erp_assets");
		// $assets = $erp_assets->find()->hydrate(false)->toArray();
		
		// $number = '000000000';
		// foreach($assets as $asset)
		// {
			// $row = $erp_assets->get($asset['asset_id']);
			// $code = $row['asset_code'];
			
			// $c = explode("/",$code);
			// $number = (int) $number;
			// $c4 = str_pad(++$number,9,'0',STR_PAD_LEFT);
			// $new_code = $c[0].'/'.$c[1].'/'.$c[2].'/'.$c4;
			
			// $row['asset_code'] = $new_code;
			// $erp_assets->save($row);
		// }
		// die;
	// }
	
	// public function updatepodetailrow()
	// {
		// $erp_inventory_po_detail = TableRegistry::get("erp_inventory_po_detail");
		// $po_rows = $erp_inventory_po_detail->find()->where(["approved"=>1])->select(['id'])->hydrate(false)->toArray();
		
		// $i = 0;
		// foreach($po_rows as $row)
		// {
			// $detail_row = $erp_inventory_po_detail->get($row['id']);
			// $detail_row->grn_remain_qty = $detail_row->quantity;
			// $erp_inventory_po_detail->save($detail_row);
			// $i++;
		// }
		// debug($i);
		// debug(count($po_rows));die;
	// }
	/*
	public function joinmaterial()
	{
		// $this->autoRender = false;
		// $data = $this->request->data;
		$erp_material = TableRegistry::get("erp_material");
		$join_hstr = TableRegistry::get("erp_join_material_history");
		// $master_material = $data['material_id'];
		// $base_material = $data['base_material'];
		
		$master_material = 3;
		$base_material = 3174;
		
		$master_material_data = $erp_material->get($master_material);
		$base_material_data = $erp_material->get($base_material);
		
		$join_material = $base_material_data->toArray();
		$row = $join_hstr->newEntity($join_material);
		$row['join_with_material'] = $master_material;
		$row['join_by'] = $this->request->session()->read('user_id');
		$row['join_date'] = date("Y-m-d");
		$save_history = $join_hstr->save($row);
		
		if($save_history)
		{
			######### Start code for update master material id on base material id ###########
			
			// Change material opening stock
			$erp_stock_history=TableRegistry::get('erp_stock_history');
			$base_material_opening_stock = $erp_stock_history->find()->where(['material_id'=>$base_material,'type'=>'os'])->hydrate(false)->toArray();
			
			if(!empty($base_material_opening_stock))
			{
				foreach($base_material_opening_stock as $base_stock)
				{
					//Check master material stock history records with base material project 
					$master_project_stock = $erp_stock_history->find()->where(['material_id'=>$master_material,'project_id'=>$base_stock['project_id'],'type'=>'os'])->first();
					
					//If !empty then remove base material record and update quentity in master
					if(!empty($master_project_stock))
					{
						$master_stock_update = $erp_stock_history->get($master_project_stock->stock_id);
						$total_stock = $master_stock_update->quantity + $base_stock['quantity'];
						$master_stock_update->quantity = $total_stock; 
						$updated = $erp_stock_history->save($master_stock_update);
						if($updated)
						{
							$stock_tbl_deleted = TableRegistry::get("erp_stock_history_deleted");
							$del_row = $stock_tbl_deleted->newEntity();
							$del_row = $stock_tbl_deleted->patchEntity($del_row,$base_stock);
							if($stock_tbl_deleted->save($del_row))
							{
								$delete_stock = $erp_stock_history->get($base_stock['stock_id']);
								$deleted = $erp_stock_history->delete($delete_stock);
							}
							
						}
					}else{
						//if master material have not record with particular project then update material id with master 
						$query12 = $erp_stock_history->query();
						$query12->update()
						->set(['material_id'=>$master_material])
						->where(['stock_id' =>$base_stock['stock_id']])
						->execute();
					}
				}
			}
			
			//Update material id in erp_stock_history
			$erp_stock_history=TableRegistry::get('erp_stock_history');
			$query = $erp_stock_history->query();
			$query->update()
			->set(['material_id'=>$master_material])
			->where(['material_id' =>$base_material])
			->execute();
			
			//Update material id in erp_stock_history_deleted
			$erp_stock_history_deleted=TableRegistry::get('erp_stock_history_deleted');
			$query1 = $erp_stock_history_deleted->query();
			$query1->update()
			->set(['material_id'=>$master_material])
			->where(['material_id' =>$base_material])
			->execute();
			
			//Update material id in erp_finalized_rate_detail
			$erp_finalized_rate_detail=TableRegistry::get('erp_finalized_rate_detail');
			$query2 = $erp_finalized_rate_detail->query();
			$query2->update()
			->set(['material_id'=>$master_material])
			->where(['material_id' =>$base_material])
			->execute();
			
			//Update material id in erp_inventory_po_detail
			$erp_inventory_po_detail=TableRegistry::get('erp_inventory_po_detail');
			$query3 = $erp_inventory_po_detail->query();
			$query3->update()
			->set(['material_id'=>$master_material])
			->where(['material_id' =>$base_material])
			->execute();
			
			//Update material id in erp_manual_po_detail
			$erp_manual_po_detail=TableRegistry::get('erp_manual_po_detail');
			$query4 = $erp_manual_po_detail->query();
			$query4->update()
			->set(['material_id'=>$master_material])
			->where(['material_id' =>$base_material])
			->execute();
			
			//Update material id in erp_inventory_pr_material
			$erp_inventory_pr_material=TableRegistry::get('erp_inventory_pr_material');
			$query5 = $erp_inventory_pr_material->query();
			$query5->update()
			->set(['material_id'=>$master_material])
			->where(['material_id' =>$base_material])
			->execute();
			
			//Update material id in erp_inventory_grn_detail
			$erp_inventory_grn_detail=TableRegistry::get('erp_inventory_grn_detail');
			$query6 = $erp_inventory_grn_detail->query();
			$query6->update()
			->set(['material_id'=>$master_material])
			->where(['material_id' =>$base_material])
			->execute();
			
			//Update material id in erp_inventory_is_detail
			$erp_inventory_is_detail=TableRegistry::get('erp_inventory_is_detail');
			$query7 = $erp_inventory_is_detail->query();
			$query7->update()
			->set(['material_id'=>$master_material])
			->where(['material_id' =>$base_material])
			->execute();
			
			//Update material id in erp_inventory_rbn_detail
			$erp_inventory_rbn_detail=TableRegistry::get('erp_inventory_rbn_detail');
			$query8 = $erp_inventory_rbn_detail->query();
			$query8->update()
			->set(['material_id'=>$master_material])
			->where(['material_id' =>$base_material])
			->execute();
			
			//Update material id in erp_inventory_mrn_detail
			$erp_inventory_mrn_detail=TableRegistry::get('erp_inventory_mrn_detail');
			$query9 = $erp_inventory_mrn_detail->query();
			$query9->update()
			->set(['material_id'=>$master_material])
			->where(['material_id' =>$base_material])
			->execute();
			
			//Update material id in erp_inventory_sst_detail
			$erp_inventory_sst_detail=TableRegistry::get('erp_inventory_sst_detail');
			$query10 = $erp_inventory_sst_detail->query();
			$query10->update()
			->set(['material_id'=>$master_material])
			->where(['material_id' =>$base_material])
			->execute();
					
			######### End code for update master material id on base material id ###########
			
			// $base_material_data['material_code'] = $master_material_data->material_code;
			// $base_material_data['material_item_code'] = $master_material_data->material_item_code;
			// $base_material_data['material_title'] = $master_material_data->material_title;
			// $base_material_data['brand_id'] = $master_material_data->brand_id;
			// $base_material_data['project_id'] = $master_material_data->project_id;
			// $base_material_data['consume'] = $master_material_data->consume;
			// $base_material_data['unit_id'] = $master_material_data->unit_id;
			// $base_material_data['desciption'] = $master_material_data->desciption;
			// $base_material_data['status'] = $master_material_data->status;
			// $join = $erp_material->save($base_material_data);
			
			// Base material delete
			$erp_material->delete($base_material_data);
			
			echo "joined";die;
	}
	}*/
	
	// public function updateisstock()
	// {
		// $erp_stock_history=TableRegistry::get('erp_stock_history');
		// $records = $erp_stock_history->find()->where(["return_back >"=>0,"stock_out >"=>0,"type"=>"is"])->hydrate(false)->toArray();
		// $i = 0;
		// foreach($records as $retrive)
		// {
			// $row = $erp_stock_history->get($retrive['stock_id']);
			// $row->stock_out = $row->return_back;
			// $row->return_back = NULL;
			// $erp_stock_history->save($row);
			// $i++;
		// }
		// echo $i;
		// echo "<br>";
		// echo "updated";die;
		
	// }
	
	// public function updateisstockmaterial()
	// {
		// $this->autoRender = false;
		// $erp_stock_history=TableRegistry::get('erp_stock_history');
		
		// $conn = ConnectionManager::get('default');		
		 // $result = $conn->execute('SELECT
		   // a.stock_id,
		   // a.material_id as stock_material_id,
		   // b.material_id as is_material_id
		// FROM erp_stock_history as a 
		// INNER JOIN erp_inventory_is_detail as b ON a.detail_id = b.is_detail_id where a.type = "is" AND a.material_id != b.material_id');		
		// $i = 0;
		// foreach($result as $retrive)
		// {
			// $row = $erp_stock_history->get($retrive['stock_id']);
			// $row->material_id = $retrive['is_material_id'];
			// $erp_stock_history->save($row);
			// $i++;
		// }
		// echo $i;
		// echo "updated";
		// die;
	// }
	// public function userreport()
	// {
		// $rows = array();
		// $rows[] = array("Employee No.","PF Slip Ref. No.","First Name","Middle Name","Last Name","Mobile No","Education","Designation","Employed at","Pay Type","Aadhar No.");
						
		// $users_table = TableRegistry::get('erp_users'); 
		// $user_list = $users_table->find()->where(["employee_no !=" => "","is_resign"=>0])->hydrate(false)->toArray();
		
		// foreach($user_list as $retrive_data)
		// {
			// $export = array();
			// $export[] = $retrive_data['user_id'];
			// $export[] = $retrive_data['pf_ref_no'];
			// $export[] = $retrive_data['first_name'];							
			// $export[] = $retrive_data['middle_name'];
			// $export[] = $retrive_data['last_name'];
			// $export[] = $retrive_data['mobile_no'];
			// $export[] = $retrive_data['education'];							
			// $export[] = $this->ERPfunction->get_category_title($retrive_data['designation']);
			// $export[] = $this->ERPfunction->get_projectname($retrive_data['employee_at']);
			// $export[] = $this->ERPfunction->get_pay_type($retrive_data['pay_type']);
			// $export[] = $retrive_data['adhaar_card_no'];
			
			// $rows[] = $export;
		// }
		// $filename = "EmployeeList.csv";
		// $this->ERPfunction->export_to_csv($filename,$rows);
		// echo "Done";die;
	// }
	
	// public function combineporecords()
	// {
		// $erp_inventory_po = TableRegistry::get('erp_inventory_po');
		// $erp_inventory_po_detail = TableRegistry::get('erp_inventory_po_detail');
		
		// $erp_manual_po = TableRegistry::get('erp_manual_po');
		// $erp_manual_po_detail = TableRegistry::get('erp_manual_po_detail');
		
		// /*Manual po data*/
		// $po_data = $erp_manual_po->find()->limit(50)->hydrate(false)->toArray();
		/* debug($po_data);die; */
		// $i = 1;
		// foreach($po_data as $retrive)
		// {
			// /* Manual PO Material Data */
			// $pod_data = $erp_manual_po_detail->find()->where(["po_id"=>$retrive["po_id"]])->hydrate(false)->toArray();
			// /* For remove from manual po table */
			// $manual_po_removable_id = $retrive["po_id"];
			/* debug($manual_po_removable_id);die; */
			// /* For remove from manual po table */
			
			// /* Remove old po id */
			// unset($retrive["po_id"]);
			
			// /* Create New Entity */
			// $retrive["po_purchase_type"] = "manual_po";
			// $entity_data = $erp_inventory_po->newEntity();			
			// $save_data=$erp_inventory_po->patchEntity($entity_data,$retrive);
			
			// if($erp_inventory_po->save($save_data))			
			// {
				// $new_po_id = $save_data->po_id;
				// if($pod_data)
				// {
					// /* Save material data */
					// foreach($pod_data as $detail_data)
					// {
						// /* Remove old po_id */
						// unset($detail_data["id"]);
						// unset($detail_data["po_id"]);
						// /* Add new po_id */
						// $detail_data["po_id"] = $new_po_id;
						// $detail_data["po_type"] = "manual_po";
						// $d_entity_data = $erp_inventory_po_detail->newEntity();			
						// $save_d_data=$erp_inventory_po_detail->patchEntity($d_entity_data,$detail_data);
						// $erp_inventory_po_detail->save($save_d_data);
					// }
				// }
			// }
			// $useless_data = $erp_manual_po->get($manual_po_removable_id);
			// $erp_manual_po->delete($useless_data);
			// $i++;
		// }
		// echo "Done with ".$i."records";die;
	// }
	
	// public function tranfergrnrecords()
	// {
		// $erp_inventory_grn = TableRegistry::get('erp_inventory_grn');
		// $erp_inventory_grn_detail = TableRegistry::get('erp_inventory_grn_detail');
		
		// $erp_audit_grn = TableRegistry::get('erp_audit_grn');
		// $erp_audit_grn_detail = TableRegistry::get('erp_audit_grn_detail');
		
		// /*GRN data*/
		// $grn_data = $erp_inventory_grn->find()->limit(50)->where(["transfer_done"=>0])->hydrate(false)->toArray();
		// /* debug($grn_data);die;  */
		// $i = 1;
		// foreach($grn_data as $retrive)
		// {
			// /* GRN Material Data */
			// $grnd_data = $erp_inventory_grn_detail->find()->where(["grn_id"=>$retrive["grn_id"]])->hydrate(false)->toArray();
					
			// /* Create New Entity */
			// $entity_data = $erp_audit_grn->newEntity();			
			// $save_data=$erp_audit_grn->patchEntity($entity_data,$retrive);
			
			// if($erp_audit_grn->save($save_data))			
			// {
				// $audit_id = $save_data->audit_id;
				// if($grnd_data)
				// {
					// /* Save material data */
					// foreach($grnd_data as $detail_data)
					// {
						// /* Remove old po_id */
						// /* Add new po_id */
						// $detail_data["audit_id"] = $audit_id;
						// $d_entity_data = $erp_audit_grn_detail->newEntity();			
						// $save_d_data=$erp_audit_grn_detail->patchEntity($d_entity_data,$detail_data);
						// $erp_audit_grn_detail->save($save_d_data);
					// }
				// }
			// }
			// $updating_grn_row = $erp_inventory_grn->get($retrive["grn_id"]);
			// $updating_grn_row->transfer_done = 1;
			// $erp_inventory_grn->save($updating_grn_row);
			// $i++;
		// }
		// echo "Done with ".$i."records";die;
	// }
	
	/*public function updateuseridentity()
	{
		$user_tbl = TableRegistry::get("erp_users");
		$users = $user_tbl->find()->select(['user_id'])->where(["employee_no !=" => ""])->hydrate(false)->toArray();
		// debug($available_ids);die;
		
		$i = 1;
		foreach($users as $id)
		{
			$row = $user_tbl->get($id['user_id']);
			$row->user_identy_number = $id['user_id'];
			$user_tbl->save($row);
			$i++;
		}
		debug($i);die;
		die;
	}
	
	/*public function findmissingid()
	{
		$user_tbl = TableRegistry::get("erp_users");
		$available_ids = $user_tbl->find()->select(['user_identy_number'])->hydrate(false)->toArray();
		// debug($available_ids);die;
		
		$available_id = array();
		foreach($available_ids as $id)
		{
			if($id['user_identy_number'] != '')
			{
				$available_id[] = $id['user_identy_number'];
			}
			
		} 
		
		// construct a new array:1,2....max(given array).
		$missing_ids = range(1,max($available_id));                                                    

		// use array_diff to get the missing elements 
		$missing = array_diff($missing_ids,$available_id);
		// debug($missing);die;
		if(!empty($missing))
		{ 
			$next_number = reset($missing);
			debug($next_number);die;
		}else{
			$query = $user_tbl->find();
			$data = $query
				->select(["user_identy_number" => $query->func()->max('user_identy_number')]);
			$result = $data->first();
			debug($result->user_identy_number + 1);die;
		}
	}*/
	
	/*public function approveisaudit()
	{
		$erp_is_audit = TableRegistry::get("erp_is_audit");
		$erp_audit_is_detail = TableRegistry::get("erp_audit_is_detail");
		$is_data = $erp_is_audit->find()->select(['audit_is_id'])->limit(10)->where(["project_id =" => 22,"changes_status"=>0])->hydrate(false)->toArray();
		
		$i = 1;
		foreach($is_data as $id)
		{
			$audit_id = $id['audit_is_id'];
			$delete_ok = $erp_audit_is_detail->deleteAll(["is_audit_id"=>$audit_id]);
			if($delete_ok)
			{
				$row = $erp_is_audit->get($audit_id);
				$ok = $erp_is_audit->delete($row);
				$i++;
			}
		}
		debug($i);die;
		die;
	}*/
	
	// public function changestatusofoldbill()
	// {
		// $table_register_inward_bill=TableRegistry::get('erp_inward_bill');
		// $records = $table_register_inward_bill->find()->limit(200)->where(["status_inward"=>"completed"])->select(["inward_bill_id"])->hydrate(false)->toArray();
		// $i = 0;
		// foreach($records as $req_id)
		// {
			// if($req_id['inward_bill_id'] != '')
			// {
				// $row = $table_register_inward_bill->get($req_id['inward_bill_id']);
				// $row['status_inward'] = 'Check with A/C Dept.';
				// $check=$table_register_inward_bill->save($row);
			// }
			// $i++;
		// }
		
		// echo $i." records updated successfully";die;
	// }
	
	// public function updategrnqtystock()
	// {
		// $erp_inventory_grn_detail=TableRegistry::get('erp_inventory_grn_detail');
		// $erp_stock_history=TableRegistry::get('erp_stock_history');
		// $records = $erp_stock_history->find()->limit(50)->where(["quantity IS NULL","type"=>"grn"])->select(["stock_id","material_id","material_name","type_id"])->hydrate(false)->toArray();
	
		// $i = 0;
		// foreach($records as $row)
		// {
			// if($row['material_id'] && $row['type_id'])
			// {
				// $grn_row = $erp_inventory_grn_detail->find()->where(["material_id"=>$row['material_id'],"grn_id"=>$row['type_id']])->first();
			// }else{
				// $grn_row = $erp_inventory_grn_detail->find()->where(["material_name"=>$row['material_name'],"grn_id"=>$row['type_id']])->first();
			// }
			// if(!empty($grn_row))
			// {
				// $stock_row = $erp_stock_history->get($row['stock_id']);
				// if($grn_row['actual_qty'])
				// {
					// $stock_row->quantity = $grn_row['actual_qty'];
					// $stock_row->stock_in = $grn_row['actual_qty'];
					// $save = $erp_stock_history->save($stock_row);
				// }
			// }
			// $i++;
		// }
		
		// echo $i." records updated successfully";die;
	// }
	
	// public function updatepogrnqty()
	// {
		// $erp_inventory_po=TableRegistry::get('erp_inventory_po');
		// $erp_inventory_po_detail=TableRegistry::get('erp_inventory_po_detail');
		//// $records = $erp_inventory_po->find()->limit(50)->where(["po_date >="=>"2020-04-01","po_purchase_type"=>"manual_po"])->select(["po_date","po_id","po_purchase_type"])->hydrate(false)->toArray();
		// $records = $erp_inventory_po->find()->where(["po_date >="=>"2020-04-01","po_purchase_type"=>"manual_po"])->select(["po_date","po_id","po_purchase_type"])->hydrate(false)->toArray();
		// debug($records);
		// $i = 0;
		// foreach($records as $row)
		// {
			// $detail_row = $erp_inventory_po_detail->find()->where(["po_id"=>$row['po_id']])->hydrate(false)->toArray();
			
			// if(!empty($detail_row))
			// {
				// foreach($detail_row as $drow)
				// {
					// $m_row = $erp_inventory_po_detail->get($drow['id']);
					// $m_row->grn_remain_qty = $m_row->quantity;
					// $save = $erp_inventory_po_detail->save($m_row);
				// }
			// }
			// $i++;
		// }
		
		// echo $i." records updated successfully";die;
	// }
	public function	querytest() {
		
		$stockHistory=TableRegistry::get('erp_stock_history');
		$stockDetails = $stockHistory->find()->select(array('project_id','material_id'))->limit(1);
		foreach ($stockDetails as $title) {
			echo $title;
			$stockRecord = $stockHistory->find()->where(array('project_id' => $title['project_id'],'material_id'=>$title['material_id']))->limit(1);
			foreach ($stockRecord as $stockrecord) {
				echo $stockrecord;
			}
		}
		die;
	}
	// public function updateAgencyToVendor() {
	// 	$erpAgency=TableRegistry::get('erp_agency');
	// 	$erpVendor=TableRegistry::get('erp_vendor');
	// 	$erpDetails = $erpAgency->find('all')->select()->where(array('insert_status'=> 0))->limit(1);
	// 	foreach ($erpDetails as $erpRecord) {
			// echo $erpRecord;die;
			// $id = $erpRecord['id'];
			// $agencyId = $erpRecord['agency_id'];
			// $data = $erpVendor->newEntity();
			// $code = $this->get_last_vendor_id();
			// $vdno = "YNEC/VD/". sprintf("%09d", $code + 1);
			// debug($vdno);die;
			// $data -> vendor_group = 0;
			// $data -> vendor_id = $vdno;
			// $data -> vendor_name = $erpRecord['agency_name'];
			// $data -> vendor_billing_address = $erpRecord['agency_billing_address'];
			// $data -> contact_no1 = $erpRecord['contact_no'];
			// $data -> email_id = $erpRecord['email_id'];
			// $data -> pancard_no = $erpRecord['pancard_no'];
			// $data -> vat_tin_no = $erpRecord['vat_tin_no'];
			// $data -> service_tax_no = $erpRecord['service_tax_no'];
			// $data -> cst_no = $erpRecord['cst_no'];
			// $data -> gst_no = $erpRecord['gst_no'];
			// $data -> ac_no = $erpRecord['ac_no'];
			// $data -> bank_name = $erpRecord['bank_name'];
			// $data -> branch_name = $erpRecord['branch_name'];
			// $data -> ifsc_code = $erpRecord['ifsc_code'];
			// $transferType = $erpRecord['transfer_type'];
			// $data -> created_date = $erpRecord['created_date'];
			// $data -> created_by = $erpRecord['created_by'];
			// $data -> last_edit = $erpRecord['last_edit'];
			// $data -> last_edit_by = $erpRecord['last_edit_by'];
			// $data -> status = $erpRecord['status'];
			// $data -> remove_date = $erpRecord['remove_date'];
			// $data -> remove_by = $erpRecord['remove_by'];
			// $data -> attach_label = $erpRecord['attach_label'];
			// $data -> attach_file = $erpRecord['attach_file'];
			// debug($data);die;
			// $woTable = TableRegistry::get('erp_vendor');
			// $woDetails = $woTable->find('all')->select()->limit(1);
			// foreach($woDetails as $data) {
			// 	debug($data['vendor_id']);
			// }
			// die;
			// if($erpVendor->save($data)){
				// echo "Data saved";
				// $erpVendor=TableRegistry::get('erp_vendor');
				// $vendorId = $data->user_id;
				
				// $vendorRecords = $erpVendor->get($vendorId);
				
				// $userId = $vendorRecords['user_id'];
				// $partyId = $vendorRecords['vendor_id'];

				// $woTable = TableRegistry::get('erp_planning_work_order');
				// $woDetails = $woTable->query();
				// $woDetails->update()->set(["party_userid"=>$userId,"party_id" => $partyId])->where(['party_id'=>$agencyId])->execute();
				
				// $erpSubContract = TableRegistry::get('erp_sub_contract');
				// $erpSubContractDetails = $erpSubContract -> query();
				// $erpSubContractDetails->update()->set(["party_id"=>$userId,"party_identy" => $partyId])->where(['party_identy'=>$agencyId])->execute();
				
	// 			$erpWorkOrder = TableRegistry::get('erp_work_order');
	// 			$erpWorkOrderDetails = $erpWorkOrder ->query();
	// 			$erpWorkOrderDetails->update()->set(["party_userid"=>$userId,"party_id" => $partyId])->where(['party_id'=>$agencyId])->execute();
				
	// 			$erpInwardBillRegister = TableRegistry::get('erp_inward_bill');
	// 			$erpInwardBillDetails = $erpInwardBillRegister ->query();
	// 			$erpInwardBillDetails->update()->set(["party_name"=>$userId,"party_id" => $partyId])->where(['party_id'=>$agencyId])->execute();
				
	// 			$erpDebitNote = TableRegistry::get("erp_debit_note");
	// 			$erpDebitNoteDetails = $erpDebitNote ->query();
	// 			$erpDebitNoteDetails->update()->set(["debit_to" => $partyId])->where(['debit_to'=>$agencyId])->execute();
				
	// 			$erpAdvanceRequest = TableRegistry::get("erp_advance_request_detail");
	// 			$erpAdvanceRequestDetails = $erpAdvanceRequest->query();
	// 			$erpAdvanceRequestDetails->update()->set(["agency_id" => $userId])->where(['agency_id'=>$id])->execute();
				
	// 			$erpInventoryIs = TableRegistry::get('erp_inventory_is');
	// 			$erpInventoryIsDetails = $erpInventoryIs->query();
	// 			$erpInventoryIsDetails->update()->set(["agency_name" => $userId])->where(['agency_name'=>$id])->execute();
				
	// 			$erpInventoryRbn = TableRegistry::get('erp_inventory_rbn');
	// 			$erpInventoryRbnDetails = $erpInventoryRbn->query();
	// 			$erpInventoryRbnDetails->update()->set(["agency_name" => $userId])->where(['agency_name'=>$id])->execute();
				
	// 			$erpInventoryDebitNote = TableRegistry::get('erp_inventory_debit_note');
	// 			$erpInventoryDebitNoteDetails = $erpInventoryDebitNote->query();
	// 			$erpInventoryDebitNoteDetails->update()->set(["debit_to" => $userId])->where(['debit_to'=>$id])->execute();
				
	// 			$erpInventoryRmc = TableRegistry::get('erp_inventory_rmc'); 
	// 			$erpInventoryRmcDetails = $erpInventoryRmc->query();
	// 			$erpInventoryRmcDetails->update()->set(["agency_id" => $userId])->where(['agency_id'=>$id])->execute();
					
	// 			$erpAgency=TableRegistry::get('erp_agency');
	// 			$retrive_data = $erpAgency->find('all')->select()->where(array('agency_id' => $agencyId));
	// 			foreach ($retrive_data as $status) {
	// 				$status-> insert_status = 1;
	// 				$erpAgency->save($status);
	// 				echo "Status Updated ". $agencyId;
	// 			}				
	// 		}
	// 	}
	// 	die;
	// }
	// public function get_last_vendor_id() {
	// 	$conn = ConnectionManager::get('default');
	// 	$result = $conn->execute('select max(user_id) from erp_vendor');		
	// 	$max = 0;
	// 	foreach($result as $retrive_data) {
	// 		$max=$retrive_data[0];
	// 	}
	// 	return $max;
	// }

	// public function updategsttotal() {
	// 	// Update GST value in erp_inventory_po_detail
	// 	$erpInventoryPoDetail=TableRegistry::get('erp_inventory_po_detail');
	// 	$erpDetails = $erpInventoryPoDetail->find('all')->select()->where(array('update_status'=> 0))->limit(5);
	// 	foreach($erpDetails as $retrive_data) {
	// 		$erpInventoryPoDetailId = $retrive_data->id;
	// 		$erpInventoryPoId = $retrive_data->po_id;
	// 		$erpInventoryPoDetailRecords = $erpInventoryPoDetail->get($erpInventoryPoDetailId);
	// 		$sum = '';
	// 		if($erpInventoryPoDetailRecords['transportation'] == '0' && $erpInventoryPoDetailRecords['exice'] == '0') {
	// 			// $sum = $erpInventoryPoDetailRecords['transportation']+$erpInventoryPoDetailRecords['exice']+$erpInventoryPoDetailRecords['other_tax'];
	// 			// $result = $erpInventoryPoDetail->query();
	// 			// $result->update()->set(["gst" => $sum,"update_status"=>1])->where(['id'=>$erpInventoryPoDetailId])->execute();
	// 			// echo "Status Updated". $erpInventoryPoDetailId;
				
	// 			$result = $erpInventoryPoDetail->query();
	// 			$result->update()->set(["gst" => $erpInventoryPoDetailRecords['other_tax'],"update_status"=>1])->where(['id'=>$erpInventoryPoDetailId])->execute();
	// 			$erpInventoryPo = TableRegistry::get('erp_inventory_po');
	// 			$erpInventoryPoDetails = $erpInventoryPo->query();
	// 			$erpInventoryPoDetails->update()->set(['mode_of_gst'=>'IGST'])->where(['po_id'=>$erpInventoryPoId])->execute();
	// 			echo "Status Updated". $erpInventoryPoDetailId;
	// 			echo "<br>";
	// 			echo "PO update" . $erpInventoryPoId;

	// 		}else {
	// 			$sum = $erpInventoryPoDetailRecords['transportation']+$erpInventoryPoDetailRecords['exice']+$erpInventoryPoDetailRecords['other_tax'];
	// 			$result = $erpInventoryPoDetail->query();
	// 			$result->update()->set(["gst" => $sum,"update_status"=>1])->where(['id'=>$erpInventoryPoDetailId])->execute();
	// 			$erpInventoryPo = TableRegistry::get('erp_inventory_po');
	// 			$erpInventoryPoDetails = $erpInventoryPo->query();
	// 			$erpInventoryPoDetails->update()->set(['mode_of_gst'=>'CGST+SGST'])->where(['po_id'=>$erpInventoryPoId])->execute();
	// 			echo "Status Updated". $erpInventoryPoDetailId;
	// 			echo "<br>";
	// 			echo "PO update" . $erpInventoryPoId;
	// 		}
	// 	}
	// 	die;
	// }

	// Enable All project for 
	// public function	enableprojectforcategory() {
		
	// 	$erpCategoryMaster=TableRegistry::get('erp_category_master');
	// 	$projectIds = $erpCategoryMaster->find()->where(['change_done'=>0])->select(array('cat_id'))->limit(600)->hydrate(false)->toArray();
	// 	if(!empty($projectIds))
	// 	{
	// 		foreach($projectIds as $projectId)
	// 		{
	// 			$row = $erpCategoryMaster->get($projectId['cat_id']);
	// 			$project = json_encode(array('2'));
	// 			$row->project_id = $project;
	// 			$row->change_done = 1;
	// 			$erpCategoryMaster->save($row);
	// 			debug($row->cat_id);
	// 		}
	// 	}else{
	// 		echo "done";
	// 	}
	// 	die;
	// }

	public function enablepoupdatestatus() {
		$erpInventoryPoDetails = TableRegistry::get("erp_inventory_po_detail");
		$poDetailsData = $erpInventoryPoDetails->find()->select(['po_id'])->where(['approved !=' => 0])->hydrate(false)->toArray();
		foreach($poDetailsData as $result) {
			debug($result);

			$erpInventoryPo = TableRegistry::get("erp_inventory_po");
			$status = $erpInventoryPo->query();
			$status->update()->set(['ammend_approve' => 1,"updated" => 0])->where(["po_id" => $result['po_id']])->execute();
			// $poData = $erpInventoryPo->find()->select(["po_id"])->where(['approved'])
		}
		die;
	}

	public function isAuthorized($user)
	{
		return true;
		return parent::isAuthorized($user);
	}

}
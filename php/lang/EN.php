<?php
# GluTax Translations
# Lang: EN

define("_NAME", "GluTax");

define("_NAVBAR_NEW_PURCHASE", "New Purchase");
define("_NAVBAR_TABLE", "Tables");
define("_NAVBAR_OPTION", "Options");
define("_NAVBAR_REPORT", "Reports");

define("_TABLE_CATEGORY", "Category");
define("_TABLE_CATEGORIES", "Categories");
define("_TABLE_CATEGORY_HELP", "Add a category to each of your purchases, for a better summary on reports.");
define("_TABLE_PERSON", "Person");
define("_TABLE_PERSON_HELP", "You may often buy products by yourself, and sometime, other can buy them for you. Track who is the buyer of each expenses for a more accurate tracking.");
define("_TABLE_PRODUCT", "Product");
define("_TABLE_PRODUCT_HELP", "Record informations about products you often buy, to avoid re-entering the same infos again and again.");
define("_TABLE_STORE", "Store");
define("_TABLE_STORE_HELP", "Record informations about stores you often go, to avoid re-entering the same infos again and again.");

define("_OPTION_SETTING", "Settings");
define("_OPTION_SYSTEM", "System");
define("_OPTION_LOGOUT", "Logout");

define("_HOME_WELCOME", "Welcome");
define("_HOME_SHORT_MESSAGE", _NAME.", your tracking solution for gluten-free expenses.");
define("_HOME_RECENT_PURCH", "Recent Purchases");

define("_LABEL_NAME", "Name");
define("_LABEL_ADDRESS", "Adress / Location");
define("_LABEL_CATEGORY", _TABLE_CATEGORY);
define("_LABEL_PRODUCT_GF", "Gluten-free product");
define("_LABEL_PRODUCT_GF_SHORT", _TABLE_PRODUCT);
define("_LABEL_PRODUCT_EQU", "Equivalent Product");
define("_LABEL_PRODUCT_EQU_SHORT", "Equivalent");
define("_LABEL_PURCH_DATE", "Purchase Date");
define("_LABEL_STORE", _TABLE_STORE);
define("_LABEL_PERSON", _TABLE_PERSON);
define("_LABEL_PURCH_EXTRA", "Extra Amount");
define("_LABEL_PURCH_EXTRA_SHORT", "Extra");
define("_LABEL_PURCH_NEW", _NAVBAR_NEW_PURCHASE);
define("_LABEL_PURCH_EDIT", "Modify a Purchase");
define("_LABEL_PURCH_INFO", "Purchase info");
define("_LABEL_DESCRIPTION", "Description");
define("_LABEL_QUANTIY", "Quantity");
define("_LABEL_PRICE_UNIT", "Price per unit");
define("_LABEL_PRICE", "Price");
define("_LABEL_SIZE", "Format");
define("_LABEL_SIZE_HELP", "Format (eg. 200 ml, enter 200)");
define("_LABEL_FORMAT", "Measure");
define("_LABEL_FORMAT_HELP", "Measure (eg. 200 ml, select millilitres)");
define("_LABEL_NOTE", "Note");
define("_LABEL_REF", "Reference");
define("_LABEL_PRODUCTS", _TABLE_PRODUCT.'s');
define("_LABEL_SUMMARY", "Summary");
define("_LABEL_SUMMARY_COUNT_PRODUCT", "Product Count");
define("_LABEL_SUMMARY_TOTAL_EXTRA", "Total Amount");
define("_LABEL_G", "grams");
define("_LABEL_ML", "millilitres");
define("_LABEL_U", "units");
define("_LABEL_CHOOSE", "Choose...");
define("_LABEL_PURCH_ADD_PRODUCT", "Add a Product");
define("_LABEL_PURCH_CHG_PRODUCT", "Edit a Product");
define("_LABEL_SKU", "Product ID or SKU");
define("_LABEL_OR", "Or");
define("_LABEL_PURCH_DETAILS_QUICK", "Quick View");
define("_LABEL_LOAD_LIST", "Choose from list");
define("_LABEL_LOAD_CODE", "Enter a code/SKU");
define("_LABEL_NOT_FOUND", "not found!");
define("_LABEL_YTD", "Year to Date");
define("_LABEL_METRICS_PAID_ON_PURCH", "Paid in extra on %s purchases.");
define("_LABEL_NEW_STORE", "Enter the new store name");

define("_SETTING_TITLE", "Settings");
define("_SETTING_SUBTITLE", "Customize "._NAME." to make it works the way you want !");
define("_SETTING_YOU", "You");
define("_SETTING_YOU_NAME", "Your Mame");
define("_SETTING_YOU_NAME_HELP", "This is the name that will be used on reports and to identify you.  You can enter your real name or a nickname, as you wish.");
define("_SETTING_YOU_EMAIL", "Your Email Address");
define("_SETTING_YOU_EMAIL_HELP", "The email "._NAME." can use to reach you, in case you forget your password.  Your email will be kept private and will never be shared.");
define("_SETTING_REGION", "Language and Region");
define("_SETTING_REGION_LANG", "Language");
define("_SETTING_REGION_LANG_HELP", "Language to use for "._NAME." interface.");
define("_SETTING_REGION_LOCALE", "Region");
define("_SETTING_REGION_LOCALE_HELP", "Choose the region that fits your local preference.  That will be used to format amounts and dates for you.");
define("_SETTING_MISC", "Other preferences");
define("_SETTING_MISC_USE_PC", "Product Category");
define("_SETTING_MISC_USE_PC_HELP", "Add a 'Category' prompt in Products.");
define("_SETTING_MISC_USE_PERSONS", "Persons");
define("_SETTING_MISC_USE_PERSONS_HELP", "Add a 'Buyer' prompt in Purchases.");
define("_SETTING_MISC_DEFAULT_PDATE", "Default Purchase Date Offset");
define("_SETTING_MISC_DEFAULT_PDATE_HELP", "To define the default Purchase Date in a new Purchase, decrease this number from the current date.");
define("_SETTING_MISC_CONFIRM_PN", "Confirm Purchase Number");
define("_SETTING_MISC_CONFIRM_PN_HELP", "Display the new Purchase Number in a Message instead of only a small Toast.");
define("_SETTING_MISC_LINES_WELCOME", "Number of Lines in Recent Purchases");
define("_SETTING_MISC_LINES_WELCOME_HELP", "Number of recent Purchases to display in the Welcome Screen.");
define("_SETTING_MISC_LINES_REPORTS", "Number of Lines in one Report Page");
define("_SETTING_MISC_LINES_REPORTS_HELP", "Number of lines to display in a report before breaking to a new page.");
define("_SETTING_ON", "Activate");
define("_SETTING_OFF", "Deactivate");


define("_REPORT_PURCH_ALL_SUMMARY", "All Purchases Summarized");
define("_REPORT_PURCH_ALL_DETAILS", "All Purchases Details");

define("_BUTTON_EXPORT", "Export");
define("_BUTTON_ADD_NEW", "Add New");
define("_BUTTON_ADD_PRODUCT", "Product Entry");
define("_BUTTON_ADD_PRODUCT_TABLE", "Load a Product");
define("_BUTTON_DELETE", "Delete");
define("_BUTTON_CANCEL", "Cancel");
define("_BUTTON_SAVE", "Save");
define("_BUTTON_UPDATE", "Update");
define("_BUTTON_CONFIRM", "Are you sure ?");
define("_BUTTON_CLOSE", "Close");
define("_BUTTON_NEXT", "Next");

define("_TOAST_PURCH_ADDED", "Purchase saved");
define("_TOAST_TABLE_ADDED", "New item added");
define("_TOAST_TABLE_UPDATED", "Item updated");

?>
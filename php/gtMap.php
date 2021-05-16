<?php 
# SQL Map for easier PHP handling.

# Tables
define("_SQL_ACC", "tbaccount");
define("_SQL_CAT", "tbcategory");
define("_SQL_EXP", "tbexpense");
define("_SQL_PER", "tbperson");
define("_SQL_PUR", "tbpurchase");
define("_SQL_PRO", "tbproduct");
define("_SQL_STO", "tbstore");

# Fields in tbAccount
define("_SQL_ACC_ID", "accountID");
define("_SQL_ACC_NAME", "accountName");
define("_SQL_ACC_EMAIL", "accountEmail");
define("_SQL_ACC_PWD", "accountPasswd");
define("_SQL_ACC_LANG", "accountLanguage");
define("_SQL_ACC_LOCAL", "accountLocale");
define("_SQL_ACC_NEXT_PURCH", "accountNextPurchNo");
define("_SQL_ACC_USE_PC", "accountUsePC");
define("_SQL_ACC_USE_PERSONS", "accountUsePersons");
define("_SQL_ACC_DEF_PDATE_OS", "accountDefaultPDateOffset");
define("_SQL_ACC_CONF_PN", "accountConfirmPN");
define("_SQL_ACC_LINES_WELCOME", "accountLinesWelcome");
define("_SQL_ACC_LINES_REPORTS", "accountLinesReport");

# Fields in tbCategory
define("_SQL_CAT_ID", "categoryID");
define("_SQL_CAT_ACCOUNT", "categoryAccountID");
define("_SQL_CAT_NAME", "categoryName");

# Fields in tbPerson
define("_SQL_PER_ID", "personID");
define("_SQL_PER_ACCOUNT", "personAccountID");
define("_SQL_PER_NAME", "personName");

# Fields in tbStore
define("_SQL_STO_ID", "storeID");
define("_SQL_STO_ACCOUNT", "storeAccountID");
define("_SQL_STO_NAME", "storeName");
define("_SQL_STO_ADDRESS", "storeAddress");

# Fields in tbProduct
define("_SQL_PRO_ID", "productID");
define("_SQL_PRO_ACCOUNT", "productAccountID");
define("_SQL_PRO_CATEGORY", "productCategoryID");
define("_SQL_PRO_SKU", "productSKU");
define("_SQL_PRO_NAME", "productName");
define("_SQL_PRO_PRICE", "productPrice");
define("_SQL_PRO_SIZE", "productSize");
define("_SQL_PRO_FORMAT", "productFormat");
define("_SQL_EQU_NAME", "productEquName");
define("_SQL_EQU_SIZE", "productEquSize");
define("_SQL_EQU_PRICE", "productEquPrice");

# Fields in tbPurchase
define("_SQL_PUR_ID", "purchaseID");
define("_SQL_PUR_ACCOUNT", "purchaseAccountID");
define("_SQL_PUR_NUMBER", "purchaseNumber");
define("_SQL_PUR_DATE", "purchaseDate");
define("_SQL_PUR_REF", "purchaseReference");
define("_SQL_PUR_PERSON", "purchasePersonID");
define("_SQL_PUR_STORE", "purchaseStoreID");
define("_SQL_PUR_AMT_EQU", "purchaseAmountNormal");
define("_SQL_PUR_AMT_GF", "purchaseAmountGF");
define("_SQL_PUR_AMT_EXTRA", "purchaseAmountExtra");
define("_SQL_PUR_NOTE", "purchaseNote");

# Fields in tbExpense
define("_SQL_EXP_ID", "expenseID");
define("_SQL_EXP_ACCOUNT", "expenseAccountID");
define("_SQL_EXP_PURCHASE", "expensePurchaseID");
define("_SQL_EXP_LINE", "expenseLineID");
define("_SQL_EXP_PRODUCT", "expenseProductID");
define("_SQL_EXP_PRO_NAME", "expenseProductName");
define("_SQL_EXP_QUANTITY", "expenseQuantity");
define("_SQL_EXP_PRO_SIZE", "expenseProductSize");
define("_SQL_EXP_PRO_FORMAT", "expenseProductFormat");
define("_SQL_EXP_PRO_PRICE", "expensePrice");
define("_SQL_EXP_EQU_NAME", "expenseEquName");
define("_SQL_EXP_EQU_SIZE", "expenseEquProductSize");
define("_SQL_EXP_EQU_PRICE", "expenseEquPrice");
define("_SQL_EXP_EXTRA", "expenseExtra");
define("_SQL_EXP_NOTE", "expenseNote");
define("_SQL_EXP_PRO_PP100", "expenseProductPP100");
define("_SQL_EXP_EQU_PP100", "expenseEquPP100");
define("_SQL_EXP_DIFF_PP100", "expenseDiffPP100");

?>
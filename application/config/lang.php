<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
*/
$config['websiteLang'] = [
    "ProjectProductController" => [
        "selectProduct" => ["select_product"],
        "productDetails" => ["product_detail"],
        "editRoomSelectProduct" => ["select_product"],
        "productDetailsEdit" => ["product_detail"],
        "AccessoryProduct" => ["accessory_products"],
        "accessoryProductDetail" => ["product_detail"],
        "selectedProjectProducts" => ["select_product"],
    ],
    "QuickCalcLuxController" => [
        "luxValues" => ["quickcal"],
        "selectProduct" => ["select_product"],
        "selectArticle" => ["product_detail"],
        "articleDetails" => ["article_details"],
        "view_result" => ["view_result"],
    ],
    "TechnicianController" => [
        "index" => ["technicians"],
        "details" => ["technicians"],
        "request_list" => ["technicians"],
    ],
    "CompaniesController" => [
        "companies" => ["companies"],
        "favoriteCompanies" => ["companies"],
        "companyDetails" => ["companies", "inspirations"],
    ],
    "NotificationController" => [
        "index" => ["notifications"],
    ],
    "ProjectRequestController" => [
        "installerListing" => ["installer_listing"],
    ],
    "QuotesController" => [
        "index" => ["quotation_listing"],
        "customerQuotesList" => ["quotation_listing"],
        "customerQuotesListViaProject" => ["quotation_listing"],
        "awaiting" => ["quotation_listing"],
        "submitted" => ["quotation_listing"],
        "approved" => ["quotation_listing"],
        "project_details" => ["project_details"],
        "projectCreateRoomListing" => ["project_room_listing"],
        "editProject" => ["edit_project"],
        "selectedProjectProducts" => ["accessory_products"],
        "projectResultRoomListing" => [""],
        "view_result" => ["view_result"],
        "levelsListing" => ["level_listing"],
        "tco" => ["tco"],
    ],
    "UserController" => [
        "profile" => ["user"],
        "edit_profile" => ["user"],
        "settings" => ["user"],
    ],
    "Home" => [
    ],
    "ProductArticlesController" => [
        "articleDetails" => ["article_details"],
        "editRoomArticleDetail" => ["article_details"],
        "accessoryArticleDetail" => ["article_details"],
    ],
    "ProjectRoomsController" => [
        "projectCreateRoomListing" => ["project_room_listing"],
        "projectResultRoomListing" => ["project_room_listing"],
        "applications" => ["applications"],
        "roomType" => ["rooms"],
        "dimensions" => ["quickcalc"],
        "editDimensions" => ["quickcalc"],
    ],
    "SearchArticlesController" => [
        "search" => ["search"],
        "QuickCal" => ["quickcalc"],
        "view_result" => ["view_result"],
    ],
    "Index" => [
        "index" => ["login"],
        "forgot" => ["forgot"],
        "signup" => ["signup"],
        "resetsuccess" => ["password"],
        "forgotsuccess" => ["password"],
        "resetpassword" => ["password"],
    ],
    "ProjectController" => [
        "index" => ["project_listing"],
        "create" => ["project_form"],
        "edit" => ["project_form"],
        "applications" => ["applications"],
        "rooms" => ["rooms"],
        "room_type" => ["rooms"],
        "view_result" => ["view_result"],
        "project_details" => ["project_details"],
    ],
    "QuickCalcController" => [
        "applications" => ["applications"],
        "rooms" => ["rooms"],
        "quickcalc" => ["quickcalc"],
        "view_result" => ["view_result"],
    ],
    "SearchController" => [
    ],
    "InspirationController" => [
        "index" => ["inspirations"],
        "details" => ["inspirations"],
        "add" => ["inspiration_forms"],
        "edit" => ["inspiration_forms"],
    ],
    "ProjectLevelsController" => [
        "levelsListing" => ["level_listing"],
    ],
    "QuickCalcLuminaryController" => [
        "luxValues" => ["quickcalc"],
        "selectProduct" => ["select_product"],
        "selectArticle" => ["product_detail"],
        "articleDetails" => ["article_details"],
        "view_result" => ["view_result"],
    ],
    "TcoController" => [
        "tco" => ["tco"],
    ]
];

<?php

namespace Database\Seeders;

use App\Models\admin\Module;
use Illuminate\Database\Seeder;
use App\Models\admin\ModuleAccess;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::truncate();
        ModuleAccess::truncate();
        $modules = [
            [
                "identifiers"   => "log_system",
                "name"          => "Log System",
                "access"        => [
                    [
                        "identifiers" => "view",
                        "name"        => "View",
                    ],
                    [
                        "identifiers" => "clear",
                        "name"        => "Clear",
                    ],
                    [
                        "identifiers" => "export",
                        "name"        => "Export",
                    ],
                ]
            ],
            [
                "identifiers"   => "user_group",
                "name"          => "User Group",
                "access"        => [
                    [
                        "identifiers" => "view",
                        "name"        => "View",
                    ],
                    [
                        "identifiers" => "add",
                        "name"        => "Add",
                    ],
                    [
                        "identifiers" => "edit",
                        "name"        => "Edit",
                    ],
                    [
                        "identifiers" => "delete",
                        "name"        => "Delete",
                    ],
                    [
                        "identifiers" => "detail",
                        "name"        => "Detail",
                    ],
                    [
                        "identifiers" => "status",
                        "name"        => "Status",
                    ]
                ]
            ],
            [
                "identifiers"   => "user",
                "name"          => "User",
                "access"        => [
                    [
                        "identifiers" => "view",
                        "name"        => "View",
                    ],
                    [
                        "identifiers" => "add",
                        "name"        => "Add",
                    ],
                    [
                        "identifiers" => "edit",
                        "name"        => "Edit",
                    ],
                    [
                        "identifiers" => "delete",
                        "name"        => "Delete",
                    ],
                    [
                        "identifiers" => "detail",
                        "name"        => "Detail",
                    ],
                    [
                        "identifiers" => "status",
                        "name"        => "Status",
                    ],
                    [
                        "identifiers" => "arsip",
                        "name"        => "Arsip",
                    ],
                    [
                        "identifiers" => "restore",
                        "name"        => "Restore",
                    ],
                ]
            ],
            [
                "identifiers"   => "profile",
                "name"          => "Profile",
                "access"        => [
                    [
                        "identifiers" => "view",
                        "name"        => "View",
                    ],
                ]
            ],
            [
                "identifiers"   => "module_management",
                "name"          => "Module Management",
                "access"        => [
                    [
                        "identifiers" => "view",
                        "name"        => "View",
                    ],
                    [
                        "identifiers" => "add",
                        "name"        => "Add",
                    ],
                    [
                        "identifiers" => "edit",
                        "name"        => "Edit",
                    ],
                    [
                        "identifiers" => "delete",
                        "name"        => "Delete",
                    ],
                    [
                        "identifiers" => "detail",
                        "name"        => "Detail",
                    ],
                ]
            ],
            [
                "identifiers"   => "settings",
                "name"          => "Settings",
                "access"        => [
                    [
                        "identifiers" => "main",
                        "name"        => "Main",
                    ],
                    [
                        "identifiers" => "frontpage",
                        "name"        => "Frontpage",
                    ],
                    [
                        "identifiers" => "admin",
                        "name"        => "Admin",
                    ],
                    [
                        "identifiers" => "admin_general",
                        "name"        => "Admin General",
                    ],
                ]
            ],
        ];


        foreach ($modules as $data) {
            $data_access = $data['access'];
            $data_module = [
                "identifiers"   => $data["identifiers"],
                "name"          => $data["name"]
            ];
            $module = Module::create($data_module);
            foreach ($data_access as $row) {
                $module->access()->create([
                    "identifiers" => $row["identifiers"],
                    "name"        => $row["name"]
                ]);
            }
        }
    }
}

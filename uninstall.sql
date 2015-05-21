-- add table prefix if you have one
DELETE FROM core_config_data WHERE path like 'ffuenf_devtools/%';
DELETE FROM core_resource WHERE code = 'ffuenf_devtools_setup';
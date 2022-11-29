


INSERT INTO `assets`(
    `asset_id`,
    `asset_number`,
    `asset_name`,
    `asset_type`,
    `asset_received_date`,
    `asset_service_life`,
    `asset_budget_code`,
    `asset_price`,
    `asset_description`,
    `asset_amount`,
    `asset_unit_code`,
    `asset_status_code`
)
SELECT
    NULL,
    asset_number,
    asset_name,
    asset_type,
    if(length(asset_received_date)>8,asset_received_date,null),
    asset_available_year,
    IF(length(asset_receive_type)>0,asset_receive_type,0),
    asset_amount,
    asset_description,
    1 as asset_amount,
    asset_unit,
    asset_status
FROM
	assets_clean
;



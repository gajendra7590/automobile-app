ALTER TABLE `branches`
ADD `mapping_brand_id` int(11) NULL COMMENT 'If has id then it has to map with relevent brand' AFTER `is_editable`,
ADD FOREIGN KEY (`mapping_brand_id`) REFERENCES `bike_brands` (`id`) ON DELETE NO ACTION;


ALTER TABLE `purchases`
ADD `discount_with_gst` decimal(10,2) NULL DEFAULT '0.00' COMMENT '- From Grand total' AFTER `grand_total`,
ADD `other_charges` decimal(10,2) NULL DEFAULT '0.00' COMMENT '+ In Grand total' AFTER `discount_with_gst`;

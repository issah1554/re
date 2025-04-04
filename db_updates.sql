ALTER TABLE users 
MODIFY `type` TINYINT(1) NOT NULL DEFAULT 4 COMMENT '1 = Admin, 2 = Owner, 3 = Manager, 4 = Tenant';

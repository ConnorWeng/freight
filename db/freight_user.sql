-- Create table
create table FREIGHT_USER
(
  id                     NUMBER not null,
  username               VARCHAR2(20),
  password               VARCHAR2(20),
  usertype               CHAR(1),
  create_date            DATE,
  enterprise_name        VARCHAR2(50),
  organization_code      VARCHAR2(50),
  contact_name           VARCHAR2(20),
  contact_tel            VARCHAR2(20),
  industry               VARCHAR2(50),
  business_nature        VARCHAR2(50),
  enterprise_create_date DATE,
  enterprise_address     VARCHAR2(100),
  annual_turnover        NUMBER
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );
-- Create/Recreate primary, unique and foreign key constraints
alter table FREIGHT_USER
  add constraint FREIGHT_USER_PK primary key (ID)
  using index
  tablespace SYSTEM
  pctfree 10
  initrans 2
  maxtrans 255
  storage
  (
    initial 64K
    next 1M
    minextents 1
    maxextents unlimited
  );

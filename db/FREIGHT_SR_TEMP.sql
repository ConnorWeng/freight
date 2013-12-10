-- Create table
-- Create table
create table FREIGHT_SR_TEMP
(
  batch_id     VARCHAR2(50),
  l_f_supplier VARCHAR2(100),
  buyer_name   VARCHAR2(100),
  ys_no        VARCHAR2(20),
  kp_date      VARCHAR2(20),
  ys_end_date  VARCHAR2(20),
  ori_amount   NUMBER,
  currency     VARCHAR2(20)
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
-- Create/Recreate indexes
create index FREIGHT_SR_TEMP_BATCH_INDEX on FREIGHT_SR_TEMP (BATCH_ID)
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

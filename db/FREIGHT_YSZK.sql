-- Create table
create table FREIGHT_YSZK
(
  ys_no       NUMBER not null,
  ys_type     VARCHAR2(20),
  seller      VARCHAR2(50),
  buyer_name  VARCHAR2(50),
  kp_date     DATE,
  kp_amount   NUMBER,
  currency    VARCHAR2(20),
  rmb_amount  NUMBER,
  sr_amount   NUMBER,
  hx_amount   NUMBER,
  whx_amount  NUMBER,
  buyer_rate  NUMBER,
  sr_date     DATE,
  zx_end_date DATE,
  xz_flag     VARCHAR2(1),
  xz_date     DATE
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
alter table FREIGHT_YSZK
  add constraint FREIGHT_YSZK_PK primary key (YS_NO)
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

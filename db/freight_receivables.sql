-- Create table
create table FREIGHT_RECEIVABLES
(
  receivables_number NUMBER not null,
  receivables_type   VARCHAR2(20),
  seller             VARCHAR2(50),
  buyer_name         VARCHAR2(50),
  invoice_open_date  DATE,
  invoice_price      NUMBER,
  currency           VARCHAR2(20),
  rmb_price          NUMBER,
  amount_received    NUMBER,
  amount_off         NUMBER,
  amount_unoff       NUMBER,
  buyer_proportion   NUMBER,
  received_date      DATE,
  debt_deadline      DATE,
  off_flag           VARCHAR2(1),
  off_date           DATE
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
alter table FREIGHT_RECEIVABLES
  add constraint FREIGHT_RECEIVABLES_PK primary key (RECEIVABLES_NUMBER)
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

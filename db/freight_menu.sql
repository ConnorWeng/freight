-- Create table
create table FREIGHT_MENU
(
  id     NUMBER not null,
  name   VARCHAR2(20),
  url    VARCHAR2(50),
  icon   VARCHAR2(50),
  remark VARCHAR2(50),
  sort   NUMBER,
  pid    NUMBER
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
alter table FREIGHT_MENU
  add constraint ID primary key (ID)
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
alter table FREIGHT_MENU
  add constraint PID unique (PID)
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

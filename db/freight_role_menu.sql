-- Create table
create table FREIGHT_ROLE_MENU
(
  role_id NUMBER not null,
  menu_id NUMBER not null
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
alter table FREIGHT_ROLE_MENU
  add constraint FREIGHT_ROLE_MENU_PK primary key (ROLE_ID, MENU_ID)
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

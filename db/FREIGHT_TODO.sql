-- Create table
create table FREIGHT_TODO
(
  id       NUMBER not null,
  name     VARCHAR2(200),
  content  VARCHAR2(4000),
  user_id  NUMBER,
  end_date VARCHAR2(20),
  priority NUMBER,
  status   NUMBER,
  sort     NUMBER
)
tablespace USERS
  pctfree 10
  initrans 1
  maxtrans 255
  storage
  (
    initial 64K
    minextents 1
    maxextents unlimited
  );
-- Create/Recreate primary, unique and foreign key constraints
alter table FREIGHT_TODO
  add constraint FREIGHT_TODO_PK primary key (ID)
  using index
  tablespace TS_TESTUSER
  pctfree 10
  initrans 2
  maxtrans 255;

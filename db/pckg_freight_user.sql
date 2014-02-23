create or replace package pckg_freight_user is

  -- Author  : CONNOR
  -- Created : 2/22/2014 9:50:07 PM
  -- Purpose :
  procedure add_user(username in varchar2,
                     password in varchar2,
                     create_date in varchar2,
                     enterprise_name in varchar2,
                     organization_code in varchar2,
                     contact_name in varchar2,
                     contact_tel in varchar2,
                     industry in varchar2,
                     role_id in varchar2,
                     ret_code out varchar2,
                     msg out varchar2);

  procedure edit_user(user_id in varchar2,
                     username in varchar2,
                     password in varchar2,
                     create_date in varchar2,
                     enterprise_name in varchar2,
                     organization_code in varchar2,
                     contact_name in varchar2,
                     contact_tel in varchar2,
                     industry in varchar2,
                     role_id in varchar2,
                     ret_code out varchar2,
                     msg out varchar2);

end pckg_freight_user;
/
create or replace package body pckg_freight_user is

  procedure add_user(username in varchar2,
                     password in varchar2,
                     create_date in varchar2,
                     enterprise_name in varchar2,
                     organization_code in varchar2,
                     contact_name in varchar2,
                     contact_tel in varchar2,
                     industry in varchar2,
                     role_id in varchar2,
                     ret_code out varchar2,
                     msg out varchar2) is
    user_id varchar2(100);
  begin

    user_id := freight_user_seq.nextval;

    insert into freight_user(id, username, password, create_date, enterprise_name, organization_code, contact_name, contact_tel, industry) values (user_id, username, password, create_date, enterprise_name, organization_code, contact_name, contact_tel, industry);

    insert into freight_role_user(role_id, user_id) values (role_id, user_id);

    commit;

    ret_code := '0';

  exception
    when others then
    begin
      rollback;
      ret_code := '-1';
    end;
  end;

  procedure edit_user(user_id in varchar2,
                     username in varchar2,
                     password in varchar2,
                     create_date in varchar2,
                     enterprise_name in varchar2,
                     organization_code in varchar2,
                     contact_name in varchar2,
                     contact_tel in varchar2,
                     industry in varchar2,
                     role_id in varchar2,
                     ret_code out varchar2,
                     msg out varchar2) is
  begin
  null;
  end;

end pckg_freight_user;
/
show err;

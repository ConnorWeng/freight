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

  procedure edit_user(i_user_id in varchar2,
                      i_username in varchar2,
                      i_password in varchar2,
                      i_create_date in varchar2,
                      i_enterprise_name in varchar2,
                      i_organization_code in varchar2,
                      i_contact_name in varchar2,
                      i_contact_tel in varchar2,
                      i_industry in varchar2,
                      i_role_id in varchar2,
                      o_ret_code out varchar2,
                      o_msg out varchar2);

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

  procedure edit_user(i_user_id in varchar2,
                      i_username in varchar2,
                      i_password in varchar2,
                      i_create_date in varchar2,
                      i_enterprise_name in varchar2,
                      i_organization_code in varchar2,
                      i_contact_name in varchar2,
                      i_contact_tel in varchar2,
                      i_industry in varchar2,
                      i_role_id in varchar2,
                      o_ret_code out varchar2,
                      o_msg out varchar2) is
  begin

    update freight_user set username=i_username, password=i_password, create_date=i_create_date, enterprise_name=i_enterprise_name, organization_code=i_organization_code, contact_name=i_contact_name, contact_tel=i_contact_tel, industry=i_industry where id=i_user_id;

    update freight_role_user set role_id=i_role_id where user_id=i_user_id;

    commit;

    o_ret_code := '0';

  exception
    when others then
    begin
      rollback;
      o_ret_code := '-1';
    end;
  end;

end pckg_freight_user;
/
show err;

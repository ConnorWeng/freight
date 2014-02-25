create or replace package pckg_freight_user is

  -- Author  : CONNOR
  -- Created : 2/22/2014 9:50:07 PM
  -- Purpose :
  procedure add_user(i_username in varchar2,
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

  procedure edit_user(i_user_id in varchar2,
                      i_username in varchar2,
                      i_password in varchar2,
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

  procedure add_user(i_username in varchar2,
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
    user_id varchar2(100);
    user_count int;
    user_exist exception;
  begin

    select count(1) into user_count from freight_user where username = i_username;

    if user_count > 0 then
       raise user_exist;
    end if;

    user_id := freight_user_seq.nextval;

    insert into freight_user(id, username, password, create_date, enterprise_name, organization_code, contact_name, contact_tel, industry) values (user_id, i_username, i_password, i_create_date, i_enterprise_name, i_organization_code, i_contact_name, i_contact_tel, i_industry);

    insert into freight_role_user(role_id, user_id) values (i_role_id, user_id);

    commit;

    o_ret_code := '0';

  exception
    when user_exist then
      o_ret_code := '-2';
      o_msg := 'user exists';
      raise;
    when others then
    begin
      rollback;
      o_ret_code := '-1';
      o_msg := to_char(sqlerrm);
    end;
  end;

  procedure edit_user(i_user_id in varchar2,
                      i_username in varchar2,
                      i_password in varchar2,
                      i_enterprise_name in varchar2,
                      i_organization_code in varchar2,
                      i_contact_name in varchar2,
                      i_contact_tel in varchar2,
                      i_industry in varchar2,
                      i_role_id in varchar2,
                      o_ret_code out varchar2,
                      o_msg out varchar2) is
  begin

    update freight_user set username=i_username, password=i_password, enterprise_name=i_enterprise_name, organization_code=i_organization_code, contact_name=i_contact_name, contact_tel=i_contact_tel, industry=i_industry where id=i_user_id;

    update freight_role_user set role_id=i_role_id where user_id=i_user_id;

    commit;

    o_ret_code := '0';

  exception
    when others then
    begin
      rollback;
      o_ret_code := '-1';
      o_msg := to_char(sqlerrm);
    end;
  end;

end pckg_freight_user;
/
show err;

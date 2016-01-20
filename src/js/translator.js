var dict = {
    COOKIE_KEY_LANG : "lang",
    SK : 0,
    ENG : 1,
    DEFAULT_LANGUAGE : 1,
    CURRENT : 1,
    dictionary : {},

    set : function(key, value){
        this.dictionary[key] = value;
    },

    get : function(key, toLanguageCode, index){
        var result = this.dictionary[key];
        if (typeof result == "undefined"){
            return "";
        }
        if ($.isArray(result[0])){
            return result[index % result.length][toLanguageCode];
        }
        return result[toLanguageCode];
    },

    echo : function (key, info, where) {
        if (this.get(key, this.CURRENT) != "") {
            var html = '<span data-trans-key="' + key + '">' + this.get(key, this.CURRENT) + "</span>";
            if (info != "")
                html += "<span> " + info + "</span>";
            $(where).html(html);
        }
    },

    echoError : function (key, info) {
        this.echo(key, info, '#error-message');
    },

    echoSuccess : function (key, info) {
        this.echo(key, info, '#success-message');
    },

    translateElement : function(toLanguageCode, scope){
        if (typeof toLanguageCode == "undefined" || toLanguageCode == null) {
            var cookies = document.cookie.split(';');
            for(var i = 0; i < cookies.length; i++) {
                var cookie = $.trim(cookies[i]);
                if (cookie.indexOf(this.COOKIE_KEY_LANG) == 0) {
                    toLanguageCode = parseInt(cookie.substring(this.COOKIE_KEY_LANG.length+1));
                    break;
                }
            }
            if (typeof toLanguageCode == "undefined"){
                toLanguageCode = this.DEFAULT_LANGUAGE;
            }
        }
        else {
            var now = new Date();
            now.setFullYear(now.getFullYear()+1);
            document.cookie = this.COOKIE_KEY_LANG+"="+toLanguageCode+';expires='+now.toGMTString();
        }

        this.CURRENT = toLanguageCode;

        if (typeof scope == "undefined"){
            scope = "*";
        }

        var translated = [];
        $(scope).find("[data-trans-key]").each(function() {
            var tag = $(this).attr("data-trans-key");
            if ($.inArray(tag, translated) === -1) {
                translated.push(tag);
                $(scope).find("[data-trans-key="+tag+"]").html(function(index, originalText){
                    var translation = dict.get(tag, toLanguageCode, index);
                    if ($(this).prop("tagName") === "INPUT"){
                        if ($(this).attr("type") === "submit") {
                            $(this).val(translation);
                            return originalText;
                        }
                        if ($(this).attr("type") !== "radio") {
                            $(this).attr("placeholder", translation);
                            return originalText;
                        }
                    }
                    return translation;
                });
            }
        });

        $("[data-trans-lang]").each(function() {
            $(this).css("display", (toLanguageCode === parseInt($(this).attr("data-trans-lang"))) ? "inline" : "none");
        });
    }
};

dict.set('main-header', ['Letná liga FLL', 'Summer league FLL']);
dict.set('delete', ['Odstrániť', 'Delete']);
dict.set('validate', ['Potvrdiť', 'Validate']);

dict.set('logged-in', ['Prihlásený', 'Logged in as']);
dict.set('logout', ['Odhlásiť', 'Logout']);

dict.set('assignment-page', [
    ['Riešenie možno odovzdávať do:', 'Deadline of this assingment is:'],
    ['Riešenia:', 'Solutions:']]);

/*--------------------------------------LOGIN FORM----------------------------------------*/
dict.set('login-form', [['Prihlásenie', 'Login form'],
                        ['E-mailová adresa:', 'E-mail address:'],
                        ['Heslo:', 'Password:'],
                        ['Heslo', 'Password'],
                        ['Prihlásiť sa', 'Log in'],
                        ['Registrácia', 'Registration']
]);

/*----------------------------------REGISTRATION FORM-------------------------------------*/
dict.set('reg-form', [
    ['Súťažný tím', 'Competing team'],
    ['Rozhodca', 'Jury'],
    ['Meno tímu:', 'Team name:'],
    ['Meno tímu', 'Team name'],
    ['Email:', 'Email:'],
    ['Email', 'Email'],
    ['Heslo:', 'Password:'],
    ['Heslo', 'Password'],
    ['Zopakuj heslo:', 'Repeat password:'],
    ['Zopakuj heslo', 'Repeat password'],
    ['Napíš nám niečo o sebe:', 'Write us something about yourself:'],
    ['Slovenská liga', 'Slovak league'],
    ['Open liga', 'Open league'],
    ['Registrovať', 'Register']
]);

/*----------------------------------EDIT TEAM ACCOUNT-------------------------------------*/
dict.set('edit-team-form', [
    ['Meno tímu:', 'Team name:'],
    ['Meno tímu', 'Team name'],
    ['Email:', 'Email:'],
    ['Email', 'Email'],
    ['Heslo:', 'Password:'],
    ['Heslo', 'Password'],
    ['Zopakuj heslo:', 'Repeat password:'],
    ['Zopakuj heslo', 'Repeat password'],
    ['Napíš nám niečo o sebe:', 'Write us something about yourself:'],
    ['Slovenská liga', 'Slovak league'],
    ['Open liga', 'Open league'],
    ['Uložiť', 'Save']
]);

/*----------------------------------EDIT JURY ACCOUNT-------------------------------------*/
dict.set('edit-jury-form', [
    ['Email:', 'Email:'],
    ['Email', 'Email'],
    ['Heslo:', 'Password:'],
    ['Heslo', 'Password'],
    ['Zopakuj heslo:', 'Repeat password:'],
    ['Zopakuj heslo', 'Repeat password'],
    ['Uložiť', 'Save']
]);


/*--------------------------------------NAVIGATION----------------------------------------*/
dict.set('assignment', ['Zadanie', 'Assignment']);
dict.set('assignments', ['Zadania', 'Assignments']);
dict.set('assignments-overview', ['Prehľad zadaní', 'Assignments overview']);
dict.set('results', ['Výsledky', 'Results']);
dict.set('archive', ['Archív', 'Archive']);
dict.set('users', ['Používatelia', 'Users']);
dict.set('teams', ['Tímy', 'Teams']);
dict.set('jury-pl', ['Rozhodcovia', 'Jury']);
dict.set('language', ['Jazyk', 'Language']);


/*-------------------------------------RESULT TABLE---------------------------------------*/
dict.set('table-loading', ['Tabuľka sa načítava', 'The table is loading']);
dict.set('sk-league', ['Slovenská liga', 'Slovak league']);
dict.set('open-league', ['Open liga', 'Open league']);
dict.set('team-name', ['Názov tímu', 'Team name']);
dict.set('sum-points', ['Spolu', 'Sum']);

/*-----------------------------------SOLUTION EDIT*--------------------------------------*/
dict.set('solution-edit-page', [['Prílohy:', 'Attachments:'],
    ['Typ', 'Type'],
    ['Názov', 'Name'],
    ['Link', 'Link'],
    ['Zmaž', 'Delete'],
    ['Pridaj videá k riešeniu zo serveru Youtube (Každé video vlož do nového riadku.)', 'Attach videos to your solution from Youtube (Insert every video to a new line.)'],
    ['Nahraj súbory (Veľkosť súboru nemôže presiahnúť 10 MB)', 'Upload files (Maximum filesize is 10 MB)']]);

/*--------------------------------------MESSAGES------------------------------------------*/
dict.set('non-existent-acc', ['Neexistuje účet zaregistrovaný na tento e-mail!', 'No account is registered with this e-mail!']);
dict.set('wrong-password', ['Zadali ste nesprávne heslo!', 'You have entered a wrong password!']);
dict.set('jury-acc-not-validated', ['Tento rozhodcovský účet ešte nebol potvrdený!', 'This jury account has not been validated yet!']);
dict.set('db-connection-fail', ['Nepodarilo sa spojiť s databázovým serverom!', 'It was not possible to connect to the database server!']);
dict.set('db-query-fail', ['Počas získavania údajov z databázy došlo k chybe!', 'An error has occured during the execution of a database query!']);
dict.set('db-choice-fail', ['Nepodarilo sa vybrať databázu!', 'It was not possible to connect to selected database!']);
dict.set('err-name-duplicate', ['Zadané meno sa nachádza v databáze!', 'The entered name is already taken!']);
dict.set('err-email-duplicate', ['Zadaný email sa nachádza v databáze!', 'The entered email is already taken!']);
dict.set('err-no-name', ['Nezadali ste meno!', 'You have not entered a name!']);
dict.set('err-password-match', ['Zadané heslá sa nezhodujú!', 'The passwords do not match!']);
dict.set('err-no-email', ['Nezadali ste email!', 'You have not entered an email!']);
dict.set('err-invalid-email', ['Zlý formát emailu!', 'Invalid email format!']);
dict.set('err-no-password', ['Nezadali ste heslo!', 'You have not entered a password!']);
dict.set('m-registration-success', ['Boli ste úspešne zaregistrovaný!', 'Your account has been succesfully registered!']);
dict.set('err-registration', ['Nastala chyba pri registrácii!.', 'An error has occured during the registration!']);
dict.set('m-attachment-deleted', ['Odstránenie prílohy prebehlo úspešne.', 'The attachment was succesfully deleted.']);
dict.set('err-attachment-deletion', ['Chyba pri odstraňovaní súboru.', 'An error has occured during the attachment deletion.']);
dict.set('err-attachment-db-deletion', ['Chyba pri odstraňovaní prílohy z databázy.', 'A database error has occured during the attachment deletion.']);
dict.set('err-attachment-not-in-db', ['Príloha na odstránenie sa nenašla v databáze.', 'The attachment to be deleted could not be found in the database.']);
dict.set('m-acc-deleted', ['Účet bol zmazaný.', 'Account has been deleted.']);
dict.set('err-acc-deletion', ['Účet sa NEpodarilo zmazať z databázy.', 'It was not possible to delete the account.']);
dict.set('m-acc-validated', ['Účet bol potvrdený.', 'Account has been validated.']);
dict.set('err-acc-validation', ['Účet sa nepodarilo potvrdiť.', 'It was not possible to validate the account.']);
dict.set('m-changes-saved', ['Zmeny boli ulozené do databázy.', 'The changes have been saved to the database.']);
dict.set('err-changes-saving', ['Nepodarilo sa uložiť zmeny.', 'It was not possible to save the changes.']);
dict.set('err-too-many-attachments', ['Počet príloh presiahol maximálny povolený počet.', 'You have exceeded the maximum number of attachments.']);
dict.set('m-file-uploaded', ['Úspešné nahratie súboru', 'Successful upload of file']);
dict.set('err-file-upload', ['Nepodarilo sa nahrať súbor', 'It wasnot possible to upload file']);
dict.set('err-file-upload-db', ['Do databázy as nepodarilo vložiť súbor', 'An error has occured during the database insertion of file']);
dict.set('err-file-too-big', ['Limit 10 MB na prílohu bol prekročený súborom', 'The following file has exceeded the maximum filesize of 10 MB']);
dict.set('m-video-uploaded', ['Úspešné nahratie videa', 'Successful upload of video']);
dict.set('err-video-upload', ['Do databázy as nepodarilo vložiť video', 'An error has occured during the database insertion of video']);

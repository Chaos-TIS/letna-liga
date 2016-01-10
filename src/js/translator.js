var dict = {
    COOKIE_KEY_LANG : "lang",
    SK : 0,
    ENG : 1,
    DEFAULT_LANGUAGE : 1,
    dictionary : {},

    set : function(key, value){
        dict.dictionary[key] = value;
    },

    get : function(key, toLanguageCode, index){
        var result = dict.dictionary[key];
        if (typeof result == "undefined"){
            return dict.translateFromPHP(key, toLanguageCode);
        }
        if ($.isArray(result[0])){
            return result[index % result.length][toLanguageCode];
        }
        return result[toLanguageCode];
    },

    translateFromPHP : function(key, toLanguageCode) {
        return $.ajax({
            cache: true,
            async: false,
            type: "POST",
            data: {key: key, lang: toLanguageCode},
            url: "includes/translations.php"
        }).responseText;
    },

    setSessionLanguage : function (languageCode){
        $.ajax({
            async: true,
            type: "POST",
            data: {lang: languageCode},
            url: "includes/setSessionLanguage.php"
        })
    },

    translate : function(toLanguageCode){
        if (typeof toLanguageCode == "undefined") {
            var cookies = document.cookie.split(';');
            for(var i = 0; i < cookies.length; i++) {
                var cookie = $.trim(cookies[i]);
                if (cookie.indexOf(dict.COOKIE_KEY_LANG) == 0) {
                    toLanguageCode = parseInt(cookie.substring(dict.COOKIE_KEY_LANG.length+1));
                    break;
                }
            }
            if (typeof toLanguageCode == "undefined"){
                toLanguageCode = dict.DEFAULT_LANGUAGE;
            }
        }
        else {
            document.cookie = dict.COOKIE_KEY_LANG+"="+toLanguageCode;
        }

        dict.setSessionLanguage(toLanguageCode);

        var translated = [];
        $("[data-trans]").each(function() {
            var tag = $(this).attr("data-trans");
            if ($.inArray(tag, translated) === -1) {
                translated.push(tag);
                $("[data-trans="+tag+"]").html(function(index, originalText){
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
    }
};

dict.set('main-header', ['Letná liga FLL', 'Summer league FLL']);
dict.set('delete', ['Odstrániť', 'Delete']);
dict.set('validate', ['Potvrdiť', 'Validate']);

dict.set('logged-in', ['Prihlásený', 'Account']);
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


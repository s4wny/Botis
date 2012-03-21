
                                _                
                               | |               
             _ __ ___  __ _  __| |_ __ ___   ___ 
            | '__/ _ \/ _` |/ _` | '_ ` _ \ / _ \
            | | |  __/ (_| | (_| | | | | | |  __/
            |_|  \___|\__,_|\__,_|_| |_| |_|\___|
.


    1. Installation
    2. Make an function
    3. Want to help?
    4. FAQ
    5. Sidenotes
.


    Installation
   ==============
    
    First you need PHP.
        If you read swedish or can use google translate you can read my guide @ http://4morefun.net/tips/php-i-cmd/ (worked 2012-01-11 18:12 GMT+1)
        Else it is just to download and unzip + some PATH / .bat stuff to allow you to write php everywhere.
    Then it sh
    
    ## Windows ##
        o Colors
            To get colors you need to use ANSICON or smiliar. in helpEtc/tools/ you will find ansi140.zip.
            Ansicon allow botis to use escape colors (? the thing linux have I think).
            Unpack it go to the x64 (64bit), x86(32bit) folder with CMD.
            Run ansicon.exe -i (or -h if you want to see all options).
            This will install ansicon to all users (use -i -U for current user).
            It will add a command that start ansicon.exe everytime you start up CMD (I think it is x\SOFTWARE\Microsoft\Command Processor (x = some of the root folders)).
        
        
        To just write "botis" in the command line you can edit the path in botis.bat and then just drag 'n drop it in C:\Windows\System32\ folder.
        Restart CMD. Write "botis". If it work, great! :D
        Else: *sadface* :(
    
.

    Create an function
   ==================
    ...
    
    ## Rules ##
    
        o Use a humanly syntax for your function calls.
          Look at the differents:
              Bad:  convert 255 --from dec --to hex / convert 255 -f dec -t hex
              Good: convert dec 255 to hex
          See the different?
          The first one is more, robotic, commanding. Make your function work like it was
          a human that you _talked_ with.
          
          - Why? -
              This is a GLaDOS. :)
          
              Sidenote:
                Today, 2012-01-11 17:59 GMT+1 I don't know if this will be a big and famos project.
                But if it will, and many people use it for a long time, we can in the future use OCR vocie scanning
                to run commands. Then it will be great that this GLaDOS will be easy to connect to the OCR voci scanning.
                That would be cool..
                Like in all futre movies (sciens fiction movies). Really cool, talk with a computer, wow.
            
        
        o Use ANSI as charset.
          - Why? -
              Well, I wanted UTF-8 as charset (ofc). But it doesn't work well with Windows/Cmd/PHP.
              STDIN can't read UTF-8 or something like that. The best charset I then can use that work
              is OEM 437, Extended ASCII. BUT, it has no header so you need to specific that you are
              working with OEM 437 everytime you open a file in notepad++. Trust me, you will forget
              to do that and people will not make plugins and this script will not be popular.
              So now I use ANSI, it at least allow me to use ÅÄÖ (Sweden!!1).
        
        o The filename MUST be the same as the class, that is the name of the command.
        
        o For newlines please use the (const) NL.
          E.g. `echo "A new line:". nl ." I'm on a new line!". NL ." So am I."`
          - Why? -
              Cuz then people can easy change from CR+LF to LF or vice versa.
              
        o Don't wrapp values with quotes in config.txt. Botis will do that for you.
          - Why? -
              More nice without quotes, but parse_ini_x need quotes around special chars.
        
        
    ## FAQ / Etc ##
        
        o I need to allow a english word to get a humanly syntax, does botis help me?
            Use $nullWord1, $nullWord2, ...
            Ie:
                //make table {{row1col1, row1col2} {r2c1, r2c2} {r3c1, r3c2}} with margin 10 and padding 2
                make\table::__construct($data, $nullWord1 = null, $usrOpt1 = 'margin', $usrVal1 = 0, $nullWord2 = null, $usrOpt1 = 'padding', $usrVal1 = 1);
                
                //start chat and I willl be very happy
                start\chat::__construct($nullWords = null) //Or just __construct()
    
    ## Saving data, where? ##
    
        If you want to save data, the data should be saved in the (const) DATA_FOLDER.
        Default: data/
        Save the data files to *commandName*/*whatEverUWant*.*something*
        So use a folder for your datafiles, do it _even_ if you just have one file.
       
        - Why? -
            This create a good structure. Easy to edit/remove/find databases.
    
.


    Want to help?
   ===============
    Make a function! :)
.

    FAQ
   =====
   
    o When I type special characters botis goes crasy (inf loop)
    o Some characters displays wrong, like ÅÄÖ! (windows cmd)
        Type `chcp 1252` and it will work, if not try to also change font to Consolas or Lucia Console. ;)
        
        To run `chcp 1252` everytime the prompt startup you can add an key in the registery(?):
        Go to: HKEY_LOCAL_MACHINE\SOFTWARE\Microsoft\Command Processor
        *right click* -> new -> string value (REG_SZ)
            Name  = AutoRun
            Value = `chcp 1252`
        
        For multicommands you can add an `&` between every command, ie `chcp 1252 & cls & echo Welcome!`
   
    o Config file
        o Error_lvl?
            Error_lvl don't affect PHP's error level, just botis error level.
            
            Error_lvl 0 = Don't show any botis warning/notice/error
            Error_lvl 1 = Show errors
            Error_lvl 2 = Show warnings & errors
            Error_lvl 3 = Show everything
    o $nullWord what is that?
        All $nullWords is just to allow you to write normal english. Like `convert hex 0xFF _to_ dec`, sounds better then `convert hex 0xFF dec`.
        "to" will fill out(/flesh out?) the $nullWord so "dec" will be in next variable. 

.

    Sidenotes
   ===========
    
    o Botis has no ""version number"". It has a age. It's a GLaDOS, not a shell.
    
    o ##Rules>#Humanly syntax
                Today, 2012-01-11 17:59 GMT+1 I don't know if this will be a big and famos project.
                But if it will, and many people use it for a long time, we can in the future use OCR vocie scanning
                to run commands. Then it will be great that this GLaDOS will be easy to connect to the OCR voci scanning.
                That would be cool..
                Like in all futre movies (sciens fiction movies). Really cool, talk with a computer, wow.
.


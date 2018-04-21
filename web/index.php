<!DOCTYPE html>
<html>
        <head>
                <title>GSQ+ online emulator</title>
                <style type="text/css">
                        BODY{background:#ddd;font-family:Times;}
                        .main {width:770px;} 
                        .bottom {text-align:right;}
                        .inlined {display:inline;}
                        .smaller {min-width:200px;width:200px;}
                        .fstable {min-width:190px;width:190px;}
                        .fscontainer{overflow:auto;}
                        .ogsq{resize:none;background:#000000;color:#ffffff;overflow-wrap:normal;}
                        .igsq{resize:none;height:22px;background:#000000;color:#ffffff;}
                        .errconsole{resize:none;background:#000000;color:#FFFFFF;overflow-wrap:normal;}
                        .genericbutton{background-color:#fff38e;}
                        .widthybutton{width:195px;}
                        .longestbutton{width:580px;background-color:#fff38e;}
                </style>
        </head>
        <body>
                <center>
                        <table class="main" border="1">
                                <caption>
                                        <h2>GSQ+ online emulator</h2>
                                </caption>
                                <tr>
                                        <td valign="top">
                                                <div> Please submit one of following types of files and submit them: </div>
                                                <div class="select_left">
                                                        <div>
                                                                <div class="inlined">Machine code (.BIN):</div>
                                                                <div class="inlined">
                                                                        <input id="file-input1" type="file" name="name" />
                                                                        <button onClick="machineSubmit()" class="genericbutton">Submit</button>
                                                                </div>
                                                        </div>
                                                </div>
                                        </td>
                                        <td valign="top" valign="top" class="smaller">
                                                <div class="fscontainer">
                                                        <div> Filesystem browser: </div>
                                                        <table border="1" class="fstable" id="vfstb">
                                                        </table>
                                                </div>
                                        </td>
                                        <td rowspan="2" valign="top">
                                                Load Bytecode directly:
                                                <textarea id="gsqdir" rows="20" cols="25" class="ogsq"></textarea>
                                                <button onClick="rawBytecode()" class="genericbutton widthybutton">Submit</button>
                                                <hr>
                                                GSQ+ utilities: <br>
                                                <a href="https://gist.github.com/kspalaiologos/95fe195653ceff9570470b2cc076fc13/raw/a195950847a6c97d23d5fb99a41e6a4081a141b2/sq2gsq.c">BC to BIN compiler (source)</a><div></div>
                                                <a href="https://gist.github.com/kspalaiologos/7adf30666418209990fa2ca28a4bc344/raw/d46c82d5b13b09a53545cfb9c6d000db87906536/sq2gsq.exe">BC to BIN compiler (binary)</a><div></div>
                                                <a href="https://raw.githubusercontent.com/kspalaiologos/STAR-I/master/asm2b.cpp">ASM to BC compiler (source)</a><div></div>
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <textarea id="gsqout" rows="25" cols="80" class="ogsq" readonly="readonly"></textarea>
                                                <textarea id="gsqin" rows="1" cols="80" class="igsq" onkeydown="return kdown(event)"></textarea>
                                        </td>
                                        <td valign="top">
                                                <div class="inlined">Memory size (kB): </div>
                                                <div class="inlined">
                                                        <input id="memsize" type="number" name="name" />
                                                </div>
                                                <div></div>
                                                <button onClick="document.getElementById('gsqout').value='';" class="genericbutton">Clear terminal</button>
                                                <hr>
                                                <textarea id="errcon" rows="10" cols="25" class="errconsole" readonly="readonly"></textarea>
                                                <button onClick="document.getElementById('errcon').value='';" class="genericbutton">Clear error log</button>
                                                <hr>
                                                IP:
                                                <div id="debug_ip" class="inlined">0</div>
                                                A:
                                                <div id="debug_a" class="inlined">0</div>
                                                B:
                                                <div id="debug_b" class="inlined">0</div>
                                                C:
                                                <div id="debug_c" class="inlined">0</div>
                                                <br>
                                                <hr>
                                                <input type="checkbox" id="chkbxDebug" class="genericbutton"> Debug Mode
                                                <div></div>
                                                <input type="checkbox" id="chkbxASCII" class="genericbutton"> ASCII Codes Mode
                                                <div></div>
                                                <input type="checkbox" id="chkbxBufTree" class="genericbutton"> Build Buffer Tree
                                                <div></div>
                                                <input type="checkbox" id="chkbxAsmRefresh" class="genericbutton"> Update direct BC to generated.
                                        </td>
                                </tr>
                                <tr>
                                        <td>
                                                <form action="/asm.php" method="POST" target="ifgsqo">
                                                        <textarea id="gsqasmin" class="ogsq" rows="10" cols="80" name="input"></textarea>
                                                        <input type="submit" class="longestbutton" value="Submit Assembly and compile it to bytecode (overwrite raw loading box)">
                                                </form>
                                                <iframe id="ifgsqoi" name="ifgsqo" style="width:0; height:0; border:0; border:none;">
                                                </iframe>
                                        </td>
                                </tr>
                        </table>
                </center>
                <br><br><br><br><br>
                <hr width="100%">
                <div class="bottom">
                        <font size="2">Online GSQ+ computer simulator.
                        <i>Copyright<span title="https://facebook.com/kspalaiologos42">
                        <u>Kamila Szewczyk</u></span>, Mar 2018</i></font>
                </div>
                <script>
                        document.getElementById('memsize').value = 8;
                        /*------------------------------------------------------------------*/
                        File = function (name) {
                                if (name === undefined) {} else {
                                        this.name = name;
                                        this.text = '';
                                }
                        };
                        let fs = [new File('AUTORUN.BIN')];
                        /*------------------------------------------------------------------*/
                        let outbuf='';
                        setInterval(function (){
                                if(document.getElementById('chkbxAsmRefresh').checked) {
                                        document.getElementById('gsqdir').value = document.getElementById('ifgsqoi').contentDocument.body.innerText;
                                }
                        }, 1000);
                        /*------------------------------------------------------------------*/
                        let stdin = [];
                        let stdinptr = 0;
                        for (let z = 0; z < 128; z++)
                                stdin.push(0);

                        function waitForInput() {
                                if (stdinptr == 0) return;
                                else setTimeout(0, waitForInput);
                        }

                        function kdown(event) {
                                if (event.which === 13) {
                                        let element = document.getElementById('gsqin');
                                        for (let x = 0; x < element.value.length; x++) {
                                                stdin[x + stdinptr] = element.value.charCodeAt(x);
                                        }
                                        stdin[element.value.length + stdinptr] = 13;
                                        stdinptr += element.value.length + 1;
                                        element.value = '';
                                        event.preventDefault();
                                        element = null;
                                }
                        }
                        /*------------------------------------------------------------------*/
                        let fselm = document.getElementById('vfstb');
                        fselm.innerHTML = '<tr><th>Size</th><th>Name</th></tr>';
                        fs.forEach(function (entry) {
                                fselm.innerHTML += '<tr><td>' + entry.text.length + '</td><td>' + entry.name + '</td></tr>';
                        });
                        setInterval(function () {
                                let el = document.getElementById('vfstb');
                                el.innerHTML = '<tr><th>Size</th><th>Name</th></tr>';
                                fs.forEach(function (entry) {
                                        el.innerHTML += '<tr><td>' + entry.text.length + '</td><td>' + entry.name + '</td></tr>';
                                });
                                delete el;
                        }, 1000);
                        /*------------------------------------------------------------------*/
                        function pad(str, size) {
                                while (str.length < size)
                                        str = '0' + str;
                                return str;
                        }
                        Array.prototype.clean = function(deleteValue) {
                                for (let i = 0; i < this.length; i++) {
                                        if (this[i] == deleteValue) {
                                                this.splice(i, 1);
                                                i--;
                                        }
                                }
                                return this;
                        };
                        String.prototype.reverse=function(){return this.split("").reverse().join("");}
                        /*------------------------------------------------------------------*/
                        function msglog(content) {
                                document.getElementById('errcon').value += '\n' + content;
                        }
                        /*------------------------------------------------------------------*/
                        function rawBytecode() {
                                fs[0].text = document.getElementById('gsqdir').value;
                                console.log(fs[0].text);
                                boot_bytecode();
                                delete fs[0].text;
                        }
                        /*------------------------------------------------------------------*/
                        function machineSubmit() {
                                let file = document.getElementById('file-input1').files[0];
                                if (file == undefined) {
                                        msglog('Could not read file.');
                                }
                                let fileReader = new FileReader();
                                fileReader.onload = function (e) {
                                        fs[0].text = fileReader.result;
                                        boot_machine();
                                        delete fs[0].text;
                                        delete file;
                                        delete fileReader;
                                }
                                fileReader.readAsBinaryString(file);
                        }

                        function bytecodeSubmit() {
                                let file = document.getElementById('file-input2').files[0];
                                if (file == undefined) {
                                        msglog('Could not read file.');
                                }
                                let fileReader = new FileReader();
                                fileReader.onload = function (e) {
                                        fs[0].text = fileReader.result;
                                        boot_bytecode();
                                        delete file;
                                        delete fs[0].text;
                                        delete fileReader;
                                }
                                fileReader.readAsText(file);
                        }

                        function ofunction_c(c) {
                                document.getElementById('gsqout').value += String.fromCharCode(c);
                        }

                        function ofunction_a(c) {
                                document.getElementById('gsqout').value += c + ' ';
                        }

                        function to_hex(str) {
                                let arr1 = [];
                                for (let n = 0, l = str.length; n < l; n++) {
                                        let hex = Number(str.charCodeAt(n)).toString(16);
                                        if (hex.length == 2)
                                                arr1.push(hex);
                                        else arr1.push("0" + hex);
                                }
                                return arr1.join('');
                        }

                        function ctrrev(val) {
                                return ((val & 0xFF) << 24) |
                                        ((val & 0xFF00) << 8) |
                                        ((val >> 8) & 0xFF00) |
                                        ((val >> 24) & 0xFF);
                        }

                        function boot_machine() {
                                let msize = document.getElementById('memsize').value * 1024;
                                let togenerate = msize - fs[0].text.length;
                                if (togenerate < 0) {
                                        msglog('Too small memory size!');
                                        return;
                                }
                                if (fs[0].text.length % 4 != 0) {
                                        msglog('Invalid machine code.');
                                        return;
                                }
                                let memory = [];
                                for (let x = 0; x < fs[0].text.length; x += 4) {
                                        let result = to_hex("" +
                                                String.fromCharCode(fs[0].text.charCodeAt(x + 0)) +
                                                String.fromCharCode(fs[0].text.charCodeAt(x + 1)) +
                                                String.fromCharCode(fs[0].text.charCodeAt(x + 2)) +
                                                String.fromCharCode(fs[0].text.charCodeAt(x + 3)));
                                        console.log(result);
                                        if (parseInt(result, 16) != 4294967295) {
                                                memory.push(ctrrev(parseInt(result, 16)));
                                        } else {
                                                memory.push(-1);
                                        }
                                }
                                for (let y = 0; y < togenerate; y++) {
                                        memory.push(0);
                                }
                                let ip = 0,
                                        a = 0,
                                        b = 0,
                                        c = 0,
                                        nextIP = 0,
                                        i = 0,
                                        ch = 0,
                                        debug = document.getElementById('chkbxDebug').checked,
                                        ascii = document.getElementById('chkbxASCII').checked;
                                if (debug) {
                                        function loop_dbg() {
                                                nextIP = ip + 3;
                                                a = memory[ip];
                                                b = memory[ip + 1];
                                                c = memory[ip + 2];
                                                document.getElementById('debug_ip').innerHTML = ip;
                                                        document.getElementById('debug_a').innerHTML = a;
                                                        document.getElementById('debug_b').innerHTML = b;
                                                        document.getElementById('debug_c').innerHTML = c;
                                                if (a == -1) {
                                                        if (stdin.length == 0) {
                                                                memory[b] = 0;
                                                        } else {
                                                                memory[b] = stdin[0];
                                                                stdin = stdin.substr(1);
                                                        }
                                                } else if (b == -1) {
                                                        if (!ascii)
                                                                ofunction_c(memory[a]);
                                                        else ofunction_a(memory[a]);
                                                } else {
                                                        memory[b] -= memory[a];
                                                        if (memory[b] <= 0)
                                                                nextIP = c;
                                                }
                                                ip = nextIP;
                                                if ((0 <= ip)) setTimeout(loop_dbg, 100);
                                                else {
                                                        delete msize;
                                                        delete a;
                                                        delete b;
                                                        delete c;
                                                        delete nextIP;
                                                        delete ip;
                                                        delete i;
                                                        delete ch;
                                                        delete msize;
                                                        delete togenerate;
                                                        memory = undefined;
                                                        return;
                                                }
                                        }
                                        loop_dbg();
                                } else {
                                        function loop() {
                                                nextIP = ip + 3;
                                                a = memory[ip];
                                                b = memory[ip + 1];
                                                c = memory[ip + 2];
                                                document.getElementById('debug_ip').innerHTML = ip;
                                                if (a == -1) {
                                                        if (stdin.length == 0) {
                                                                memory[b] = 0;
                                                        } else {
                                                                memory[b] = stdin[0];
                                                                stdin = stdin.substr(1);
                                                        }
                                                } else if (b == -1) {
                                                        if (!ascii)
                                                                ofunction_c(memory[a]);
                                                        else ofunction_a(memory[a]);
                                                } else {
                                                        memory[b] -= memory[a];
                                                        if (memory[b] <= 0)
                                                                nextIP = c;
                                                }
                                                ip = nextIP;
                                                if ((0 <= ip)) setTimeout(loop, 0);
                                                else {
                                                        delete msize;
                                                        delete a;
                                                        delete b;
                                                        delete c;
                                                        delete nextIP;
                                                        delete ip;
                                                        delete i;
                                                        delete ch;
                                                        delete msize;
                                                        delete togenerate;
                                                        memory = undefined;
                                                        return;
                                                }
                                        }
                                        loop();
                                }
                        }

                        function boot_bytecode() {
                                let msize = document.getElementById('memsize').value * 1024;
                                let togenerate = msize - fs[0].text.length;
                                let memory = fs[0].text.split(/\r?\n/).map(Number);
                                for (let y = 0; y < togenerate; y++) {
                                        memory.push(0);
                                }
                                let ip = 0,
                                        a = 0,
                                        b = 0,
                                        c = 0,
                                        nextIP = 0,
                                        i = 0,
                                        ch = 0,
                                        debug = document.getElementById('chkbxDebug').checked,
                                        ascii = document.getElementById('chkbxASCII').checked;
                                if (debug) {
                                        function loop_dbg() {
                                                nextIP = ip + 3;
                                                a = memory[ip];
                                                b = memory[ip + 1];
                                                c = memory[ip + 2];
                                                document.getElementById('debug_ip').innerHTML = ip;
                                                        document.getElementById('debug_a').innerHTML = a;
                                                        document.getElementById('debug_b').innerHTML = b;
                                                        document.getElementById('debug_c').innerHTML = c;
                                                if (a == -1) {
                                                        if (stdin.length == 0) {
                                                                memory[b] = 0;
                                                        } else {
                                                                memory[b] = stdin[0];
                                                                stdin = stdin.substr(1);
                                                        }
                                                } else if (b == -1) {
                                                        if (!ascii)
                                                                ofunction_c(memory[a]);
                                                        else ofunction_a(memory[a]);
                                                } else {
                                                        memory[b] -= memory[a];
                                                        if (memory[b] <= 0)
                                                                nextIP = c;
                                                }
                                                ip = nextIP;
                                                if ((0 <= ip)) setTimeout(loop_dbg, 100);
                                                else {
                                                        delete msize;
                                                        delete a;
                                                        delete b;
                                                        delete c;
                                                        delete nextIP;
                                                        delete ip;
                                                        delete i;
                                                        delete ch;
                                                        delete msize;
                                                        delete togenerate;
                                                        memory = undefined;
                                                        return;
                                                }
                                        }
                                        loop_dbg();
                                } else {
                                        function loop() {
                                                nextIP = ip + 3;
                                                a = memory[ip];
                                                b = memory[ip + 1];
                                                c = memory[ip + 2];
                                                document.getElementById('debug_ip').innerHTML = ip;
                                                if (a == -1) {
                                                        if (stdin.length == 0) {
                                                                memory[b] = 0;
                                                        } else {
                                                                memory[b] = stdin[0];
                                                                stdin = stdin.substr(1);
                                                        }
                                                } else if (b == -1) {
                                                        if (!ascii)
                                                                ofunction_c(memory[a]);
                                                        else ofunction_a(memory[a]);
                                                } else {
                                                        memory[b] -= memory[a];
                                                        if (memory[b] <= 0)
                                                                nextIP = c;
                                                }
                                                ip = nextIP;
                                                if ((0 <= ip)) setTimeout(loop, 0);
                                                else {
                                                        delete msize;
                                                        delete a;
                                                        delete b;
                                                        delete c;
                                                        delete nextIP;
                                                        delete ip;
                                                        delete i;
                                                        delete ch;
                                                        delete msize;
                                                        delete togenerate;
                                                        memory = undefined;
                                                        return;
                                                }
                                        }
                                        loop();
                                }
                        }
                </script>
        </body>
</html>

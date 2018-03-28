
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Build Status](https://travis-ci.org/kspalaiologos/GSQ-Assembly.svg?branch=master)](https://travis-ci.org/kspalaiologos/GSQ-Assembly)
[![Join the chat at https://gitter.im/GSQ-Architecture/Lobby](https://badges.gitter.im/GSQ-Architecture/Lobby.svg)](https://gitter.im/GSQ-Architecture/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)


[![wercker status](https://app.wercker.com/status/d1c87e9394ae3df09c203ccec52d6436/m/master "wercker status")](https://app.wercker.com/project/byKey/d1c87e9394ae3df09c203ccec52d6436)

GSQ is processor architecture that was aimed at low cost and high speed. GSQ-Assembly is assembly compiler for GSQ architecture. Useful links:
 * [Project Documentation](https://kamilaszewczyk.github.io/GSQ-Assembly/).
 * [Project WIKI](https://github.com/kspalaiologos/GSQ-Assembly/wiki)
 * [Issues](https://github.com/kspalaiologos/GSQ-Assembly/issues)
 * [Pull Requests](https://github.com/kspalaiologos/GSQ-Assembly/pulls)

## Features

One of the most important things out there are features. GSQ+ assembly compiler is actually the only and first compiler that was made for this architecture;

* Very low level
* Nearly direct access to hardware
* Small overhead
* Allows stacking statements

## Setup

Building this project is really simple, you just need to compile gsqasm.cpp file located in web directory. Better way to do this, would be setting up env for PHP. Then just use application frontend to use most features of this assembler.
Warning. The compiler can recognize less important syntax errors, but beware typing nonsense code! PHP code has some safeguards (that work only on Linux...). So it's advised to use it from heroku.

## Dependencies

There are no depedencies for actually building this project (PHP, GCC and some text editor have to be installed, but it's rather obvious).

## Why not GPL?

Why not use GPL (aka the Gnu Virus License)?  Well, there are three
big problems with it.  The first is that if you are a commercial
developer, and have some spare time to contribute to a freeware
product, after spending 10 hours wading through someone else's code,
getting familiar with it, and improving it or bug fixing it, all the
time you spent is wasted, as far as being able to reuse any routines
you found in a commercial product is concerned.  

The second is that encourages others to join the dog-in-the-manger 
brigade.  Someone who ordinarily would be happy to contribute something
to the public domain, once and for all, now instead goes and spends their 
effort on a GPL product, meaning the world still doesn't get the code 
freely available for ALL use (ie in public domain projects AND commercial 
projects, not JUST other GPL projects).

The third is that it is actually technology-inhibitive.  E.g. let's
say there's a GPL wordprocessor, but it doesn't support italics.
Quite a lot of people want italics, but no-one to date has been 
willing to do that work for free.  Let's say a portion of the market
wants italics.  But no one individual can afford to pay the cost of
development by themselves.  Normally this is where a company would
jump in, do the work, and then sell the new version to the market,
meaning that each individual only has to pay a fraction of the
development cost.  But the problem is that the company CAN'T just
make those changes and sell them, because it can't make those
changes proprietary, as it needs to do in order to sell them.  So
instead, the commercial operation needs to develop the entire
equivalent of the GPL wordprocessor, and THEN add italics.  But it
is too expensive for the company to do that, so the technology is
simply never developed!

GPL code will eventually become as useful as public domain code - 50 
years after the death of the original author, when it becomes public 
domain!  That's a long time to have to wait.  Until then, unless your
lawyer informs you that the 2756 license agreement conditions don't 
affect you, the GPL work is only useful as reference material.

Quoted from work of Paul Edwards:
Date:     2007-08-14
Internet: fight.subjugation@gmail.com

Actually, in my opinion GPL can be used to non-reference code - that
means, useless code that author meant to be not-free.

## Misc

This project is my attempt to create useful and full-featured processor architecture.
Actually, this is not going to be the best thing ever created, but well, it's quite promising.

## New features?

If you would want to have your own two cents in GSQ+, check out [this](https://github.com/kspalaiologos/GSQ-Assembly/blob/master/CONTRIBUTING.md) file for information.
If you'd want to add your language support, just follow instructions from the wiki. Be sure
to create a pull request! Please remember, I'm not going to merge PR's that are aganist my idea for this project.

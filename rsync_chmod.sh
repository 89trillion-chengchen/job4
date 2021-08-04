#!/bin/bash
Bdir=`dirname $(readlink -f $0)`;

chmod 777 $Bdir/logs
chmod 777 $Bdir/data
chmod 777 $Bdir/template/cache
chmod 777 $Bdir/template/compile

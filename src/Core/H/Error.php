<?php

namespace Neuron\Core\H;

// @codingStandardsIgnoreStart

/**
 * A PHP implementation of the error.h c header.
 */

class Error
{
	const EPERM				= 1;      /** Operation not permitted */
	const ENOENT			= 2;      /** No such file or directory */
	const ESRCH          = 3;      /** No such process */
	const EINTR          = 4;      /** Interrupted system call */
	const EIO 				= 5;      /** I/O error */
	const ENXIO          = 6;      /** No such device or address */
	const E2BIG          = 7;      /** Arg list too long */
	const ENOEXEC        = 8;      /** Exec format error */
	const EBADF          = 9;      /** Bad file number */
	const ECHILD         = 0;      /** No child processes */
	const EAGAIN         = 11;      /** Try again */
	const ENOMEM         = 12;      /** Out of memory */
	const EACCES         = 13;      /** Permission denied */
	const EFAULT         = 14;      /** Bad address */
	const ENOTBLK        = 15;      /** Block device required */
	const EBUSY          = 16;      /** Device or resource busy */
	const EEXIST         = 17;      /** File exists */
	const EXDEV          = 18;      /** Cross-device link */
	const ENODEV         = 19;      /** No such device */
	const ENOTDIR        = 20;      /** Not a directory */
	const EISDIR         = 21;      /** Is a directory */
	const EINVAL         = 22;      /** Invalid argument */
	const ENFILE         = 23;      /** File table overflow */
	const EMFILE         = 24;      /** Too many open files */
	const ENOTTY         = 25;      /** Not a typewriter */
	const ETXTBSY        = 26;      /** Text file busy */
	const EFBIG          = 27;      /** File too large */
	const ENOSPC         = 28;      /** No space left on device */
	const ESPIPE         = 29;      /** Illegal seek */
	const EROFS          = 30;      /** Read-only file system */
	const EMLINK         = 31;      /** Too many links */
	const EPIPE          = 32;      /** Broken pipe */
	const EDOM           = 33;      /** Math argument out of domain of func */
	const ERANGE         = 34;      /** Math result not representable */
	const EDEADLK        = 35;      /** Resource deadlock would occur */
	const ENAMETOOLONG   = 36;      /** File name too long */
	const ENOLCK         = 37;      /** No record locks available */
	const ENOSYS         = 38;      /** Function not implemented */
	const ENOTEMPTY      = 39;      /** Directory not empty */
	const ELOOP          = 40;      /** Too many symbolic links encountered */
	const EWOULDBLOCK    = 11;  	  /** Operation would block */
	const ENOMSG         = 42;      /** No message of desired type */
	const EIDRM          = 43;      /** Identifier removed */
	const ECHRNG         = 44;      /** Channel number out of range */
	const EL2NSYNC       = 45;      /** Level 2 not synchronized */
	const EL3HLT         = 46;      /** Level 3 halted */
	const EL3RST         = 47;      /** Level 3 reset */
	const ELNRNG         = 48;      /** Link number out of range */
	const EUNATCH        = 49;      /** Protocol driver not attached */
	const ENOCSI         = 50;      /** No CSI structure available */
	const EL2HLT         = 51;      /** Level 2 halted */
	const EBADE          = 52;      /** Invalid exchange */
	const EBADR          = 53;      /** Invalid request descriptor */
	const EXFULL         = 54;      /** Exchange full */
	const ENOANO         = 55;      /** No anode */
	const EBADRQC        = 56;      /** Invalid request code */
	const EBADSLT        = 57;      /** Invalid slot */
	const EDEADLOCK      = 58;      /** File locking deadlock error */
	const EBFONT         = 59;      /** Bad font file format */
	const ENOSTR         = 60;      /** Device not a stream */
	const ENODATA        = 61;      /** No data available */
	const ETIME          = 62;      /** Timer expired */
	const ENOSR          = 63;      /** Out of streams resources */
	const ENONET         = 64;      /** Machine is not on the network */
	const ENOPKG         = 65;      /** Package not installed */
	const EREMOTE        = 66;      /** Object is remote */
	const ENOLINK        = 67;      /** Link has been severed */
	const EADV           = 68;      /** Advertise error */
	const ESRMNT         = 69;      /** Srmount error */
	const ECOMM          = 70;      /** Communication error on send */
	const EPROTO         = 71;      /** Protocol error */
	const EMULTIHOP      = 72;      /** Multihop attempted */
	const EDOTDOT        = 73;      /** RFS specific error */
	const EBADMSG        = 74;      /** Not a data message */
	const EOVERFLOW      = 75;      /** Value too large for defined data type */
	const ENOTUNIQ       = 76;      /** Name not unique on network */
	const EBADFD         = 77;      /** File descriptor in bad state */
	const EREMCHG        = 78;      /** Remote address changed */
	const ELIBACC        = 79;      /** Can not access a needed shared library */
	const ELIBBAD        = 80;      /** Accessing a corrupted shared library */
	const ELIBSCN        = 81;      /** .lib section in a.out corrupted */
	const ELIBMAX        = 82;      /** Attempting to link in too many shared libraries */
	const ELIBEXEC       = 83;      /** Cannot exec a shared library directly */
	const EILSEQ         = 84;      /** Illegal byte sequence */
	const ERESTART       = 85;      /** Interrupted system call should be restarted */
	const ESTRPIPE       = 86;      /** Streams pipe error */
	const EUSERS         = 87;      /** Too many users */
	const ENOTSOCK       = 88;      /** Socket operation on non-socket */
	const EDESTADDRREQ   = 89;      /** Destination address required */
	const EMSGSIZE       = 90;      /** Message too long */
	const EPROTOTYPE     = 91;      /** Protocol wrong type for socket */
	const ENOPROTOOPT    = 92;      /** Protocol not available */
	const EPROTONOSUPPORT= 93;      /** Protocol not supported */
	const ESOCKTNOSUPPORT= 94;      /** Socket type not supported */
	const EOPNOTSUPP     = 95;      /** Operation not supported on transport endpoint */
	const EPFNOSUPPORT   = 96;      /** Protocol family not supported */
	const EAFNOSUPPORT   = 97;      /** Address family not supported by protocol */
	const EADDRINUSE     = 98;      /** Address already in use */
	const EADDRNOTAVAIL  = 99;      /** Cannot assign requested address */
	const ENETDOWN       = 100;     /** Network is down */
	const ENETUNREACH    = 101;     /** Network is unreachable */
	const ENETRESET      = 102;     /** Network dropped connection because of reset */
	const ECONNABORTED   = 103;     /** Software caused connection abort */
	const ECONNRESET     = 104;     /** Connection reset by peer */
	const ENOBUFS        = 105;     /** No buffer space available */
	const EISCONN        = 106;     /** Transport endpoint is already connected */
	const ENOTCONN       = 107;     /** Transport endpoint is not connected */
	const ESHUTDOWN      = 108;     /** Cannot send after transport endpoint shutdown */
	const ETOOMANYREFS   = 109;     /** Too many references: cannot splice */
	const ETIMEDOUT      = 110;     /** Connection timed out */
	const ECONNREFUSED   = 111;     /** Connection refused */
	const EHOSTDOWN      = 112;     /** Host is down */
	const EHOSTUNREACH   = 113;     /** No route to host */
	const EALREADY       = 114;     /** Operation already in progress */
	const EINPROGRESS    = 115;     /** Operation now in progress */
	const ESTALE         = 116;     /** Stale NFS file handle */
	const EUCLEAN        = 117;     /** Structure needs cleaning */
	const ENOTNAM        = 118;     /** Not a XENIX named type file */
	const ENAVAIL        = 119;     /** No XENIX semaphores available */
	const EISNAM         = 120;     /** Is a named type file */
	const EREMOTEIO      = 121;     /** Remote I/O error */
	const ERESTARTSYS    = 512;
	const ERESTARTNOINTR = 513;
}


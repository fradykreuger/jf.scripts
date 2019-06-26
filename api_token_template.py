#!/usr/bin/python

# this is just a template for taking in an api key as part of a script

import sys
import pathlib
import getpass

serviceDir = "linode"
apiKey = ""
apiKeyLength = 64

def main():
    doSomeSimpleChecksOnApiKeyAndLoadIt()
    printTheApiKey()

def doSomeSimpleChecksOnApiKeyAndLoadIt():
    global serviceDir, apiKey, apiKeyLength
    # establish the api.token file:
    apiKeyFile = pathlib.Path.home() /".config"/serviceDir/"api.token"

    # check if the directory exists and is 700
    if apiKeyFile.parent.exists():
        if str(apiKeyFile.parent.stat().st_mode) != "16832":
            print(str(apiKeyFile.parent) + " should be 0700")
            sys.exit()
    else:
        print(str(apiKeyFile.parent) + " does not exist")
        sys.exit()

    # check if the file exists and is 600 
    if apiKeyFile.exists():
        if str(apiKeyFile.stat().st_mode) != "33152":
            print(str(apiKeyFile) + " should be 0600")
            sys.exit()
    else:
        print(str(apiKeyFile) + " does not exist")
        sys.exit()

    # check if the string is valid
    with open(apiKeyFile, mode='r') as openedApiKey:
        # wish I knew why python puts a newline at the end of a readline()...
        apiKey = openedApiKey.readline().rstrip('\n')
        openedApiKey.close()

    if len(apiKey) != apiKeyLength:
        print("API key is invalid")
        sys.exit()

def printTheApiKey():
    global apiKey
    print(apiKey)

if __name__== "__main__":
   main()

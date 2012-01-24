/************************************************************************************************************************** 
*  a program to  encrypt a file	using the caesar cipher and a vignere cipher			                                                                      * 
*Author Brian Ward. 																	                                      *
*last modiied 26/Nov/2010                                                                                                 *
***************************************************************************************************************************/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
int const MAXWORDLENGTH=100;


void swap_characters_in_word(char *sentence);/* prototype the function definition*/
void move_character_along(char *sentence);
void move_character_back(char *sentence);
void caesar(char *sentence);
void input(int argc,char *argv[]);
void caesar_undo(char *sentence);
void vignere(char *sentence);
void vignere_undo(char *sentence);

int main(int argc, char *argv[])
{
FILE *InFile, *OutFile;
char *sentence;
sentence = (char*) malloc(10000);
int lines;
char ca;
	input( argc, argv);
	
	 //open the text files 
		 InFile = fopen(argv[2], "rb");	
		 OutFile = fopen(argv[3], "wb");
	 //check does file exist 	
		
	if ( InFile == NULL || OutFile == NULL ) /* check does file exist etc */
	{
		printf("Cannot open your files for reading, are you using the format: name.txt?\n");
		exit(1); /* terminate program */
	}
		else
		{		
			// ENCRYPTION
			 if(argv[1][0] == 'e')
			{
			
						
					printf("you want to encrypt %s\n", argv[2] );
						
						
						fread( sentence,10000,1 , InFile);//fgets(sentence,1000,InFile);
						
						//encrypt the file through the First layer of encryption			
						vignere(sentence);				
						
						//encrypt the file through the Second layer of encryption
						caesar(sentence);
						
						//encrypt the file through the Third layer of encryption
						move_character_along(sentence);
						
						//encrypt the file through the Fourth layer of encryption
						swap_characters_in_word(sentence);
								
						// send the encrypted material to outputfile
						
						printf("%s\n", sentence);
						fputs(sentence,OutFile);
					
			}	
			// DECRYPTION			
			if(argv[1][0] == 'd')		
			
			{			
					printf("you want to decrypt %s\n", argv[2] );
						
							
							 fread( sentence, 10000,1 , InFile);//fgets(sentence,1000,InFile); 
							 
							//decrypt the file through the First layer of encryption			
							swap_characters_in_word(sentence);
							
							//decrypt the file through the Second layer of encryption
							move_character_back(sentence);
									
							//decrypt the file through the Third layer of encryption
							caesar_undo(sentence);
							
							//dSecrypt the file through the  Fourth layer of encryption
							vignere_undo(sentence);
							
			
							// send the decrypted material to outputfile
							
							printf("%s", sentence);
							fputs(sentence,OutFile);
			            
			}
	
		}
	/* close file */

    fclose(InFile);
	fclose(OutFile);
return(0);
}
//function where the user inputs the data
void input(int argc, char *argv[])
{


//input file name
	/*
  **  test argc to make sure the user entered enough
  ** arguments to work with. If this number is not one of
  ** the amounts you can handle, print a usage and exit.
  */
  if ( argc != 4 )
  {
    fputs ( "usage: <program name> <encrypt[e]||decrypt[d]> <firstfile> <argv[3]>", stderr );
    exit ( EXIT_FAILURE );
  }
/* argv[0] is the program name, print it out. */

  printf ( "This Program you are using is called: %s\n", argv[0] );
  
/* print to check the file names */
		 printf ( "The operation you want is %s\n", argv[1] );
		 printf ( "First file is %s\n", argv[2] );
		 printf ( "Second file is %s\n", argv[3] );
  



}

// first level of encryption is done in this function
void swap_characters_in_word(char *sentence)
{
char *word;
 int i,j;
 word = (char*) malloc(10000);
 int len = strlen(sentence);	
							

		 for(i=0;i<=strlen(sentence);i++)
		{	
			j=0; // we initialize j with 0 because it counts the letters in an individual word	
			
					//I identify each word in the phrase and I write it in word array 
					while ((sentence[i]!='\n')&&(i<=strlen(sentence)))
					{
					word[j]=sentence[i];
					j++;
					i++;
					}
			
			// I print  the word read in the while loop in reverse order
					j=j-1;
							
				for (    ;j>=0   ;     j--)
				{
					sentence[j]=word[len-1 -j];
					
				}	
					
				sentence[len] = '\0' ;		

		}
free(word);
word = NULL;	
}			
// second level of encryption is done in this function
void move_character_along(char *sentence)
{
	int i;
    
		
       int shift= 4;// enter how many spaces on the ascii table along you wish to go
        
      
      for(i=0;i<=strlen(sentence);i++)
	  {
			while ( sentence[i] != '\0' )// 
			{
			sentence[i] = sentence[i] + shift;
			i++; 
			}	
  
		sentence[i] = '\0';			
	}		
        
}
//in this function we decrypt the second level of encryption

void move_character_back(char *sentence)
{
	int i;
    
        
		int shift = 4;// enter how many spaces on the ascii table along you wish to go
      
      for(i=0;i<=strlen(sentence);i++)
	  {
			while ( sentence[i] != '\0' )
			{
			sentence[i] = sentence[i] - shift;
			i++; 
			}	
  
		sentence[i] = '\0';			
	}		
    

}


// third level of encryption is done in this function. Here we will implement a caesar cipher				
void caesar(char *sentence)
{
int shift, shift1, i;
char key;
char array[100];
        
		
        key = 'b';// enter the letter you want to modify it with it is the letter b
       
		
		
		//convert the letter chosen to a number between zero and twenty six
		
		
        if(key >= 65 && key <= 90)//highercase
		//shift=key-64;//64?
		{shift=64;
		key = key - shift;   
		}
		
		if(key >= 97 && key <= 122)//lowercase
		{shift=96;
		key = key - shift;
		}
		
   
		// apply your shift to the
		
				for(i=0;i<=strlen(sentence);i++)
				  {
						while ( sentence[i] != '\0' )// 
							
						{
							if (sentence[i] == ' ')
							sentence[i] = ' ';
							else
							{
								if(  sentence[i]>=65 && sentence[i] <= 90 )//highercase
									{			
										sentence[i] = ((sentence[i] + key)-65)%26;
										sentence[i] += 65;
									}	
									if(sentence[i]>= 97 && sentence[i]  <= 122 )//lowercase
									{
										sentence[i] = ((sentence[i] + key)-97)%26 ;
										sentence[i] += 97;
									}
							}	
							i++; 
						}	
				  
						sentence[i] = '\0';			
				   }		
        
   
	

}

// In this function we decrypt the third level of encryption( a caesar cipher)				
void caesar_undo(char *sentence)
{		
int shift, shift1, i;
char key;
char array[100];
        
		 key = 'b';// enter the letter you want to modify it with it is the letter b
		
		//convert the letter chosen to a number between zero and twenty six
		
		
        if(key >= 65 && key <= 90)//highercase
		//shift=key-64;//64?
		{shift=64;
		key = key - shift;   
		}
		
		if(key >= 97 && key <= 122)//lowercase
		{shift=96;
		key = key - shift;
		}
		
    
	
		// apply your shift to the
      
				for(i=0;i<=strlen(sentence);i++)
				  {
						while ( sentence[i] != '\0' )// 
							
						{
							if (sentence[i] == ' ')
							sentence[i] = ' ';
							else
							{
								
								if(  sentence[i]>=65 && sentence[i] <= 90 )//highercase
									{			
										sentence[i] = ((sentence[i] + 26 - key)-65)%26;
										sentence[i] += 65;
									}	
									if(sentence[i]>= 97 && sentence[i]  <= 122 )//lowercase
									{
										sentence[i] = ((sentence[i] + 26 - key)-97)%26 ;
										sentence[i] += 97;
									}
							}	
							i++; 
						}	
				  
						sentence[i] = '\0';			
				   }			
    
   

}
//In this function we encrypt the fourth level of encryption( a Vignere cipher)
void vignere(char *sentence)
{
int shift, shift1, i, j;
char key ;
char keyword[100];
int lensen = strlen(sentence);//length
j=0;
i=0;
        
		
		//We copy in our key word here
	strcpy(keyword,"tomfoolery");
	int lenkey = strlen(keyword);//length of the keyword
	
		
		for(j=0, i=0; i<lensen ;i++, j++)// loop until all the 
		
		{
		 
			 /* if  keyword is too short wrap it*/
			if(j>=lenkey)
			{
			j=0;
			}
			//convert the characters of the chosen keyword word  to a number between zero and twenty six
								
								
				if(keyword[j] >= 65 && keyword[j] <= 90)//highercase
											
				{shift=64;
				keyword[j] = keyword[j] - shift;   
				}								
				if(keyword[j] >= 97 && keyword[j] <= 122)//lowercase
				{shift=96;
				keyword[j] = keyword[j] - shift;
				}
						
							
								if (sentence[i] == ' ')
								sentence[i] = ' ';
								else
								
								// character from input + character from keyword % 26 is encrypted charater
								{
									if(  sentence[i]>=65 && sentence[i] <= 90 )//highercase
									{			
										sentence[i] = ((sentence[i] + keyword[j])-65)%26;
										sentence[i] += 65;
									}	
									if(sentence[i]>= 97 && sentence[i]  <= 122 )//lowercase
									{
										sentence[i] = ((sentence[i] + keyword[j])-97)%26 ;
										sentence[i] += 97;
									}
								}							 			
		}
        
sentence[i] = '\0';	

}
// In this function we decrypt the Fourth level of encryption( a vignere cipher)	
void vignere_undo(char *sentence)
{
int shift, shift1, i, j;
char key ;
char keyword[100];
int lensen = strlen(sentence);
j=0;
i=0;
       //We copy in our key word here
		strcpy(keyword,"tomfoolery");
		int lenkey = strlen(keyword);//length of the keyword
		
		for(j=0, i=0; i<lensen ;i++, j++)// loop until all the 
		
		{
		 
			 /* if  keyword is too short wrap it*/
			if(j>=lenkey)
			{
			j=0;
			}
			//convert the characters of the chosen keyword word  to a number between zero and twenty six
																
				if(keyword[j] >= 65 && keyword[j] <= 90)//highercase
											
				{shift=64;
				keyword[j] = keyword[j] - shift;   
				}								
				if(keyword[j] >= 97 && keyword[j] <= 122)//lowercase
				{shift=96;
				keyword[j] = keyword[j] - shift;
				}
								if (sentence[i] == ' ')
								sentence[i] = ' ';
								else
								
								// character from input + character from keyword % 26 is encrypted charater
								{
									if(  sentence[i]>=65 && sentence[i] <= 90 )//highercase
									{			
										sentence[i] = ((sentence[i] + 26 - keyword[j])-65)%26;
										sentence[i] += 65;
									}	
									if(sentence[i]>= 97 && sentence[i]  <= 122 )//lowercase
									{
										sentence[i] = ((sentence[i] + 26 - keyword[j])-97)%26 ;
										sentence[i] += 97;
									}
								}	
							 
		}
        sentence[i] = '\0';	

}
